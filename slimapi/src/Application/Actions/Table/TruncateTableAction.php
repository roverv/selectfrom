<?php

declare(strict_types=1);

namespace App\Application\Actions\Table;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use Psr\Http\Message\ResponseInterface as Response;

class TruncateTableAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo = $this->request->getAttribute('pdo_instance');

        // Get all POST parameters
        $params = (array)$this->request->getParsedBody();

        $tables = $params['tables'] ?? [];

        $foreign_key_check_query = "SET foreign_key_checks = 0;";
        $pdo->query($foreign_key_check_query);
        $result_data['query'] = $foreign_key_check_query;

        $affected_tables = 0;
        foreach ($tables as $table) {
            try {
                $truncate_table_query = "TRUNCATE TABLE  ".QueryHelper::escapeMysqlId($table).";";
                $pdo->query($truncate_table_query);
                $result_data['query'] .= $truncate_table_query;
                $affected_tables++;
            } catch (\PDOException $e) {
                $payload = [
                  'result'  => 'error',
                  'message' => $e->getMessage(),
                  'code'    => $e->getCode(),
                ];

                return $this->respondWithData($payload);
            }
        }

        $result_data['result']          = 'success';
        $result_data['affected_tables'] = $affected_tables;

        return $this->respondWithData($result_data);
    }
}
