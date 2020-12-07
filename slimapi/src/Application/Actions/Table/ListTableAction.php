<?php

declare(strict_types=1);

namespace App\Application\Actions\Table;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class ListTableAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo = $this->request->getAttribute('pdo_instance');

          $rows  = $pdo->query("SELECT TABLE_NAME as name, TABLE_TYPE as type, ENGINE as engine, TABLE_COLLATION as collation FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME")->fetchAll();

        return $this->respondWithData($rows);
    }
}
