<?php

declare(strict_types=1);

namespace App\Application\Actions\Database;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use Psr\Http\Message\ResponseInterface as Response;

class CreateDatabaseAction extends Action
{

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo         = $this->request->getAttribute('pdo_instance');
        $post_params = (array)$this->request->getParsedBody();

        $query = "CREATE DATABASE ".QueryHelper::escapeMysqlId($post_params['database_name']);

        if (!empty($post_params['collation'])) {
            $query .= " COLLATE ".$pdo->quote($post_params['collation']);
        }

        $query .= ";";

        $result_data          = [];
        $result_data['query'] = $query;

        try {
            $pdo->query($query);
            $result_data['result'] = 'success';
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
