<?php

declare(strict_types=1);

namespace App\Application\Actions\Row;

use App\Application\Actions\Action;
use App\Application\Helpers\PdoHelper;
use App\Application\Helpers\QueryHelper;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateRowAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        //    $_POST = ['id' => '1964', 'lastname' => 'Bob en Susan2']; // test data

        $pdo          = $this->request->getAttribute('pdo_instance');
        $query_params = $this->request->getQueryParams();

        $columns_with_values = (array)$this->request->getParsedBody();

        $query = "UPDATE " . QueryHelper::escapeMysqlId($query_params['tablename']) . " SET ";

        $update_values = [];
        $binded_values = [];
        foreach ($columns_with_values as $column => $cell_value) {
            if($cell_value === null || strtolower($cell_value) === 'null') {
                $update_values[] = QueryHelper::escapeMysqlId($column) . ' = NULL ';
            }
            else {
                $update_values[] = QueryHelper::escapeMysqlId($column) . ' = ? ';
                $binded_values[] = $cell_value;
            }
        }
        $query .= implode(', ', $update_values);

        $query .= "WHERE " . QueryHelper::escapeMysqlId($query_params['column']) . " = ? ";
        $query .= "LIMIT 1 ";

        $prepare_values   = array_values($binded_values);
        $prepare_values[] = $query_params['value'];

        $pdo_statement = $pdo->prepare($query);

        $result_data = [];
        $result_data['query'] = PdoHelper::emulateSqlString($pdo_statement->queryString, $prepare_values);
        try {
            $pdo_statement = $pdo->prepare($query);
            $pdo_statement->execute($prepare_values);
            $result_data['result']  = 'success';
            $result_data['affected_rows'] = $pdo_statement->rowCount();
        } catch (Exception $e) {
            return $this->respondWithData(['message' => $e->getMessage()], 500);
        }

        return $this->respondWithData($result_data);

    }
}
