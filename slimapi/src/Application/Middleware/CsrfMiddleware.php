<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CsrfMiddleware implements Middleware
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * CsrfMiddleware constructor.
     *
     * @param  ContainerInterface  $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $guard = $this->container->get('csrf');

        $csrf_name = $request->getHeaderLine('X-CSRF-NAME');
        $csrf_value = $request->getHeaderLine('X-CSRF-VALUE');

        if (!in_array($request->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            return $handler->handle($request);
        }

        // Validate retrieved tokens
        if ($csrf_name === null
          || $csrf_value === null
          || !$guard->validateToken((string) $csrf_name, (string) $csrf_value)
        ) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error'  => 'Invalid CSRF']));

            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    }
}
