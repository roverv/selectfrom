<?php

declare(strict_types=1);

namespace App\Application\Actions\Table;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class ListSizeDataTableAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo = $this->request->getAttribute('pdo_instance');

        $rows = $pdo->query(
          "SELECT TABLE_NAME as name, TABLE_ROWS as amount_rows, (DATA_LENGTH + INDEX_LENGTH) as size, AUTO_INCREMENT as auto_increment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME"
        )->fetchAll();

        return $this->respondWithData($rows);
    }
}
