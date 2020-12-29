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
    protected function action(): Response {
        $pdo = $this->request->getAttribute('pdo_instance');

        $query = <<<SQL
SELECT
  TABLE_NAME, COLUMN_NAME, COLUMN_KEY, COLUMN_TYPE, DATA_TYPE
FROM 
  information_schema.columns
WHERE
  table_schema = DATABASE()
ORDER BY
  table_name,
  ordinal_position
SQL;

        $rows = $pdo->query($query)->fetchAll();

        $tables_with_columns = [];
        foreach ($rows as $_row) {
            $tables_with_columns[$_row['TABLE_NAME']][] = [
              'column_name' => $_row['COLUMN_NAME'],
              'column_type' => $_row['COLUMN_TYPE'],
              'data_type'   => $_row['DATA_TYPE'],
            ];
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
