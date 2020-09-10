<?php

declare(strict_types=1);

namespace App\Application\Actions\Table;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use Psr\Http\Message\ResponseInterface as Response;

class DropTableAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo = $this->request->getAttribute('pdo_instance');

        // Get all POST parameters
        $params = (array)$this->request->getParsedBody();

        $tables = $params['tables'];

        $foreign_key_check_query = "SET foreign_key_checks = 0;";
        $pdo->query($foreign_key_check_query);
        $result_data['query'] = $foreign_key_check_query;

        $affected_tables = 0;
        foreach ($tables as $table) {
            try {
                $drop_table_query = "DROP TABLE  ".QueryHelper::escapeMysqlId($table).";";
                $pdo->query($drop_table_query);
                $result_data['query'] .= $drop_table_query;
                $affected_tables++;
            } catch (Exception $e) {
                $payload = [
                  'result'  => 'error',
                  'message' => $e->getMessage(),
                ];

                return $this->respondWithData($payload);
            }
        }

        $result_data['result']          = 'success';
        $result_data['affected_tables'] = $affected_tables;

        return $this->respondWithData($result_data);
    }
}
