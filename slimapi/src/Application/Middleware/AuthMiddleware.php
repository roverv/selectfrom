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

        $host     = $session['host'] ?? '';
        $username = $session['username'] ?? '';
        $password = $session['password'] ?? '';
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
