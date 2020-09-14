<?php

declare(strict_types=1);

namespace App\Application\Actions\Row;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use Psr\Http\Message\ResponseInterface as Response;

class GetRowAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo          = $this->request->getAttribute('pdo_instance');
        $query_params = $this->request->getQueryParams();

        $query = "SELECT * FROM ".QueryHelper::escapeMysqlId($query_params['tablename'])." ";
        $query .= "WHERE ".QueryHelper::escapeMysqlId($query_params['column'])." = ? ";
        $query .= " LIMIT 1 ";

        $pdo_statement = $pdo->prepare($query);
        $pdo_statement->execute([$query_params['value']]);

        $row                = $pdo_statement->fetch();
        $table_data['data'] = $row;

        return $this->respondWithData($table_data);
    }
}
