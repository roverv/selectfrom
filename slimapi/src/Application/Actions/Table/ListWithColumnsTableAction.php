<?php

declare(strict_types=1);

namespace App\Application\Actions\Table;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class ListWithColumnsTableAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo = $this->request->getAttribute('pdo_instance');

        $rows = $pdo->query(
          "SELECT * FROM information_schema.columns WHERE table_schema = DATABASE() ORDER BY table_name,ordinal_position"
        )->fetchAll();

        $tables_with_columns = [];
        foreach ($rows as $_row) {
            $tables_with_columns[$_row['TABLE_NAME']][] = $_row['COLUMN_NAME'];
        }

        $tables_with_primary_keys = [];
        foreach ($rows as $_row) {
            if ($_row['COLUMN_KEY'] == 'PRI') {
                $tables_with_primary_keys[$_row['TABLE_NAME']] = $_row['COLUMN_NAME'];
            }
        }

        $payload = [
          'tables_with_columns'      => $tables_with_columns,
          'tables_with_primary_keys' => $tables_with_primary_keys,
        ];

        return $this->respondWithData($payload);
    }
}
