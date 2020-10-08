<?php

declare(strict_types=1);

namespace App\Application\Actions\Row;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteRowAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo          = $this->request->getAttribute('pdo_instance');
        $query_params = $this->request->getQueryParams();

        // Get all POST parameters
        $post_params = (array)$this->request->getParsedBody();

        $delete_by_column = $post_params['delete_by_column'];
        $delete_by_rows   = $post_params['delete_by_rows'];

        $affected_rows = 0;
        foreach($delete_by_rows as $row_value) {
            try {
                $query = "DELETE FROM " . QueryHelper::escapeMysqlId($query_params['tablename']) . " ";
                $query .= "WHERE " . QueryHelper::escapeMysqlId($delete_by_column) . " = ? LIMIT 1";
                $pdo_statement = $pdo->prepare($query);
                $pdo_statement->execute([$row_value]);
                $affected_rows += $pdo_statement->rowCount();
            } catch (\PDOException $e) {
                $payload = [
                  'result'        => 'error',
                  'message'       => $e->getMessage(),
                  'code'          => $e->getCode(),
                  'affected_rows' => $affected_rows, // some rows might have been successfully deleted
                ];

                return $this->respondWithData($payload);
            }
        }

        return $this->respondWithData(['result' => 'success', 'affected_rows' => $affected_rows]);
    }
}
