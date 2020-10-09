<?php

declare(strict_types=1);

namespace App\Application\Actions\Database;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use Psr\Http\Message\ResponseInterface as Response;

class DropDatabaseAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo = $this->request->getAttribute('pdo_instance');

        // Get all POST parameters
        $params = (array)$this->request->getParsedBody();

        $databases = $params['databases'];

        $result_data['query'] = "";

        $affected_databases = 0;
        foreach ($databases as $database) {
            try {
                $drop_database_query = "DROP DATABASE  ".QueryHelper::escapeMysqlId($database).";";
                $pdo->query($drop_database_query);
                $result_data['query'] .= $drop_database_query;
                $affected_databases++;
            } catch (\PDOException $e) {
                $payload = [
                  'result'  => 'error',
                  'message' => $e->getMessage(),
                  'code'    => $e->getCode(),
                  'affected_databases' => $affected_databases, // some databases might have been successfully deleted
                ];

                return $this->respondWithData($payload);
            }
        }

        $result_data['result']          = 'success';
        $result_data['affected_databases'] = $affected_databases;

        return $this->respondWithData($result_data);
    }
}
