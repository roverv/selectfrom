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

        //  $rows = $pdo->query("SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME")->fetchAll();
        $rows = $pdo->query("SHOW TABLE STATUS")->fetchAll();
        $payload = json_encode($rows);

        $this->response->getBody()->write($payload);

        return $this->response->withHeader('Content-Type', 'application/json');
    }
}
