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
    protected function action(): Response {
        $pdo = $this->request->getAttribute('pdo_instance');

        $query = <<<SQL
SELECT
  TABLE_NAME as name,
  TABLE_TYPE as type,
  ENGINE as engine,
  TABLE_COLLATION as collation
FROM
  information_schema.TABLES
WHERE
  TABLE_SCHEMA = DATABASE()
ORDER BY
  TABLE_NAME
SQL;

        $rows = $pdo->query($query)->fetchAll();

        // add boolean for support for foreign keys
        $rows_payload = array_map(function($row) {
            $tmp_row = $row;
            $tmp_row['has_foreign_key_support'] = ($row['engine'] == 'InnoDB') ? true : false;
            return $tmp_row;
        }, $rows);

        return $this->respondWithData($rows_payload);
    }
}
