<?php

declare(strict_types=1);

namespace App\Application\Actions\Row;

use App\Application\Actions\Action;
use App\Application\Helpers\PdoHelper;
use App\Application\Helpers\QueryHelper;
use Psr\Http\Message\ResponseInterface as Response;

class InsertRowAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo          = $this->request->getAttribute('pdo_instance');
        $query_params = $this->request->getQueryParams();

        $columns_with_values = (array)$this->request->getParsedBody();

        $query = "INSERT INTO " . QueryHelper::escapeMysqlId($query_params['tablename']) . " ( ";

        $escaped_columns = array_map(function ($column) {
            return QueryHelper::escapeMysqlId($column);
        }, array_keys($columns_with_values));

        $query .= implode(', ', $escaped_columns);

        $query .= ') VALUES (';

        $insert_values = [];
        $binded_values = [];
        foreach ($columns_with_values as $column => $cell_value) {
            if ($cell_value === null || strtolower($cell_value) === 'null') {
                $insert_values[] = 'NULL';
            } else {
                $insert_values[] = '?';
                $binded_values[] = $cell_value;
            }
        }
        $query .= implode(', ', $insert_values);
        $query .= ');';

        $prepare_values = array_values($binded_values);

        $pdo_statement = $pdo->prepare($query);

        $result_data          = [];
        $result_data['query'] = PdoHelper::emulateSqlString($pdo_statement->queryString, $prepare_values);

        try {
            $pdo_statement = $pdo->prepare($query);
            $pdo_statement->execute($prepare_values);
            $result_data['result']          = 'success';
            $result_data['inserted_row_id'] = $pdo->lastInsertId();
        } catch (\PDOException $e) {
            $payload = [
              'result'  => 'error',
              'message' => $e->getMessage(),
              'code'    => $e->getCode(),
            ];

            return $this->respondWithData($payload);
        }

        return $this->respondWithData($result_data);
    }
}
