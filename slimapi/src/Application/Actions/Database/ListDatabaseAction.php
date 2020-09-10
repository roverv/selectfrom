<?php

declare(strict_types=1);

namespace App\Application\Actions\Database;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class ListDatabaseAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo = $this->request->getAttribute('pdo_instance');

        $query     = "SELECT SCHEMA_NAME AS database_name, DEFAULT_COLLATION_NAME AS database_collation FROM INFORMATION_SCHEMA.SCHEMATA";
        $rows      = $pdo->query($query)->fetchAll();
        $databases = array_map(
          function ($row) {
              return ['name' => $row['database_name'], 'collation' => $row['database_collation']];
          },
          $rows
        );

        return $this->respondWithData($databases);
    }
}
