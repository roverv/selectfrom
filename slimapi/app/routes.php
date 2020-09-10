<?php

declare(strict_types=1);

use App\Application\Actions\Database\ListDatabaseAction;
use App\Application\Actions\Auth\ConnectAction;

use App\Application\Middleware\AuthMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options(
      '/{routes:.*}',
      function (Request $request, Response $response) {
          // CORS Pre-Flight OPTIONS Request Handler
          return $response;
      }
    );

    $app->post('/connect', ConnectAction::class);

    $app->group(
      '/database',
      function (Group $group) {
          $group->get('/list', ListDatabaseAction::class);
      }
    )->add(AuthMiddleware::class);
};
