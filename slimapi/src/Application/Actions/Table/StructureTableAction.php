<?php

declare(strict_types=1);

namespace App\Application\Actions\Table;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use Psr\Http\Message\ResponseInterface as Response;

class StructureTableAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo          = $this->request->getAttribute('pdo_instance');
        $query_params = $this->request->getQueryParams();

        $query = "SHOW FULL COLUMNS FROM ".QueryHelper::escapeMysqlId($query_params['tablename']);
        $rows  = $pdo->query($query)->fetchAll();

        return $this->respondWithData($rows);
    }
}
