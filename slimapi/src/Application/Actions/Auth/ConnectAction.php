<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use App\Application\Actions\Action;
use PDO;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ConnectAction extends Action
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param  LoggerInterface  $logger
     * @param  ContainerInterface  $container
     */
    public function __construct(LoggerInterface $logger, ContainerInterface $container)
    {
        parent::__construct($logger);
        $this->container = $container;
    }


    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        // check server requirements
        if(extension_loaded('sodium') === false) {
            $payload = [
              'result'  => 'error',
              'message' => 'Sodium extension is not installed or loaded. PHP 7.2 by default comes with the sodium library installed, so you probably only need to enable it.',
            ];

            return $this->respondWithData($payload);
        }

        if(extension_loaded('pdo_mysql') === false) {
            $payload = [
              'result'  => 'error',
              'message' => 'PDO MySQL extension is not installed or loaded.',
            ];

            return $this->respondWithData($payload);
        }

        if(version_compare(PHP_VERSION, '7.2.0') === -1) {
            $payload = [
              'result'  => 'error',
              'message' => sprintf('PHP 7.2 is required, you are running %s', PHP_VERSION),
            ];

            return $this->respondWithData($payload);
        }

        $params = (array)$this->request->getParsedBody(); // post
        //            $params = $this->request->getQueryParams(); // get

        $host     = (!empty($params['host'])) ? $params['host'] : 'localhost';
        $username = $params['username'] ?? '';
        $password = $params['password'] ?? '';
        $charset  = 'utf8mb4';

        //        $dsn     = "mysql:host=$host;dbname=$db;charset=$charset";
        $dsn     = "mysql:host=$host;charset=$charset";
        $options = [
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          // this must be true if we want to run running multiple queries from one sql text
          PDO::ATTR_EMULATE_PREPARES   => true,
        ];
        try {
            $pdo = new PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            $payload = [
              'result'  => 'error',
              'message' => $e->getMessage(),
              'code'    => $e->getCode(),
            ];

            return $this->respondWithData($payload);
        }

        // session fixation protection, see: https://stackoverflow.com/questions/5081025/php-session-fixation-hijacking
        session_regenerate_id(true);

        $_SESSION['host']     = $host;
        $_SESSION['username'] = $username;

        // https://paragonie.com/blog/2017/06/libsodium-quick-reference-quick-comparison-similar-functions-and-which-one-use#crypto-secretbox
        if (!empty($_COOKIE['password_key'])) {
            $key = $_COOKIE['password_key'];
        } else {
            $key = random_bytes(32);
            setcookie("password_key", $key, time() + 3600 * 24 * 90, "", "", false, true); // 3 months
        }

        if (!empty($_COOKIE['password_nonce'])) {
            $nonce = $_COOKIE['password_nonce'];
        } else {
            $nonce = random_bytes(24);
            setcookie("password_nonce", $nonce, time() + 3600 * 24 * 90, "", "", false, true); // 3 months
        }

        $password_encrypted = sodium_crypto_secretbox($password, $nonce, $key);
        $_SESSION['password'] = $password_encrypted;

        // generate CSRF token
        $csrf = $this->container->get('csrf');
        $keyPair = $csrf->generateToken();

        $payload = [
          'result' => 'success',
          'csrf_token' => $keyPair,
        ];

        return $this->respondWithData($payload);
    }
}
