<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use PDO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware implements Middleware
{
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $session = $request->getAttribute('session');

        if (empty($_COOKIE['password_key']) || empty($_COOKIE['password_nonce'])) {
            $response_json = json_encode(
              [
                'result'  => 'error',
                'message' => 'Login data expired, please login again',
              ]
            );

            $response = new \Slim\Psr7\Response();
            $response->getBody()->write($response_json);

            return $response->withHeader('Content-Type', 'application/json');
        }

        $key = $_COOKIE['password_key'];
        $nonce = $_COOKIE['password_nonce'];
        $decrypted_password = sodium_crypto_secretbox_open($session['password'] ?? '', $nonce, $key);

        $host     = $session['host'] ?? '';
        $username = $session['username'] ?? '';
        $password = $decrypted_password ?: '';
        $charset  = 'utf8mb4';

        $query_parameters = $request->getQueryParams();

        $dsn = "mysql:host=$host;charset=$charset";
        if (!empty($query_parameters['db'])) {
            $dsn = "mysql:host=$host;dbname=" . $query_parameters['db'] . ";charset=$charset";
        }
        $options = [
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          // this must be true if we want to run running multiple queries from one sql text
          PDO::ATTR_EMULATE_PREPARES   => true,
        ];
        try {
            $pdo     = new PDO($dsn, $username, $password, $options);
            $request = $request->withAttribute('pdo_instance', $pdo);
        } catch (\PDOException $e) {
            $response_json = json_encode(
              [
                'result'  => 'error',
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
              ]
            );

            $response = new \Slim\Psr7\Response();
            $response->getBody()->write($response_json);

            return $response->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    }
}
