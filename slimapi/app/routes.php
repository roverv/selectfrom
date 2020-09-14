<?php

declare(strict_types=1);

use App\Application\Actions\Database\ListDatabaseAction;
use App\Application\Actions\Auth\ConnectAction;

use App\Application\Actions\Row\ListRowAction;
use App\Application\Actions\Table\DropTableAction;
use App\Application\Actions\Table\ListTableAction;
use App\Application\Actions\Table\ListWithColumnsTableAction;
use App\Application\Actions\Table\TruncateTableAction;
use App\Application\Middleware\AuthMiddleware;
use App\Application\Middleware\CsrfMiddleware;
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
    )->add(CsrfMiddleware::class)->add(AuthMiddleware::class);

    $app->group(
      '/table',
      function (Group $group) {
          $group->get('/list', ListTableAction::class);
          $group->get('/listwithcolumns', ListWithColumnsTableAction::class);
          $group->post('/truncate', TruncateTableAction::class);
          $group->post('/drop', DropTableAction::class);
      }
    )->add(CsrfMiddleware::class)->add(AuthMiddleware::class);

    $app->group(
      '/row',
      function (Group $group) {
          $group->get('/list', ListRowAction::class);
      }
    )->add(CsrfMiddleware::class)->add(AuthMiddleware::class);
};
