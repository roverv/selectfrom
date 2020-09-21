<?php

declare(strict_types=1);

use App\Application\Actions\Database\CreateDatabaseAction;
use App\Application\Actions\Database\ListDatabaseAction;
use App\Application\Actions\Auth\ConnectAction;

use App\Application\Actions\Query\QueryAction;
use App\Application\Actions\Row\DeleteRowAction;
use App\Application\Actions\Row\GetRowAction;
use App\Application\Actions\Row\InsertRowAction;
use App\Application\Actions\Row\ListRowAction;
use App\Application\Actions\Row\UpdateRowAction;
use App\Application\Actions\Server\CollationsServerAction;
use App\Application\Actions\Table\AlterTableAction;
use App\Application\Actions\Table\CreateTableAction;
use App\Application\Actions\Table\CreationDataTableAction;
use App\Application\Actions\Table\DropTableAction;
use App\Application\Actions\Table\ListTableAction;
use App\Application\Actions\Table\ListWithColumnsTableAction;
use App\Application\Actions\Table\StructureTableAction;
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
      '/server',
      function (Group $group) {
          $group->get('/collations', CollationsServerAction::class);
      }
    )->add(CsrfMiddleware::class)->add(AuthMiddleware::class);

    $app->group(
      '/database',
      function (Group $group) {
          $group->get('/list', ListDatabaseAction::class);
          $group->post('/create', CreateDatabaseAction::class);
      }
    )->add(CsrfMiddleware::class)->add(AuthMiddleware::class);

     $app->post('/query', QueryAction::class)->add(CsrfMiddleware::class)->add(AuthMiddleware::class);

    $app->group(
      '/table',
      function (Group $group) {
          $group->get('/list', ListTableAction::class);
          $group->get('/listwithcolumns', ListWithColumnsTableAction::class);
          $group->post('/truncate', TruncateTableAction::class);
          $group->post('/drop', DropTableAction::class);
          $group->get('/structure', StructureTableAction::class);
          $group->get('/creationdata', CreationDataTableAction::class);
          $group->post('/create', CreateTableAction::class);
          $group->post('/alter', AlterTableAction::class);
      }
    )->add(CsrfMiddleware::class)->add(AuthMiddleware::class);

    $app->group(
      '/row',
      function (Group $group) {
          $group->get('/list', ListRowAction::class);
          $group->post('/delete', DeleteRowAction::class);
          $group->get('/get', GetRowAction::class);
          $group->post('/insert', InsertRowAction::class);
          $group->post('/update', UpdateRowAction::class);
      }
    )->add(CsrfMiddleware::class)->add(AuthMiddleware::class);
};
