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
        $_SESSION['password'] = $password; // @todo: encrypt this

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
