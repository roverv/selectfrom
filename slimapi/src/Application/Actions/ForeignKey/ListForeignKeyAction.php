<?php

declare(strict_types=1);

namespace App\Application\Actions\ForeignKey;


use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class ListForeignKeyAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response {
        $pdo          = $this->request->getAttribute('pdo_instance');
        $query_params = $this->request->getQueryParams();

        $payload = [];

        $query = <<<SQL
SELECT
  k.CONSTRAINT_NAME AS foreign_key_name,
  k.COLUMN_NAME AS column_name,
  k.REFERENCED_TABLE_NAME AS ref_table,
  k.REFERENCED_COLUMN_NAME AS ref_column_name,
  fc.UPDATE_RULE AS on_update,
  fc.DELETE_RULE AS on_delete
FROM
  information_schema.TABLE_CONSTRAINTS i
  JOIN information_schema.KEY_COLUMN_USAGE k
  JOIN information_schema.REFERENTIAL_CONSTRAINTS fc
WHERE
  i.CONSTRAINT_TYPE = 'FOREIGN KEY'
  AND i.TABLE_SCHEMA = DATABASE()
  AND i.TABLE_NAME = :tablename
  AND i.CONSTRAINT_NAME = k.CONSTRAINT_NAME
  AND k.TABLE_SCHEMA = DATABASE()
  AND k.TABLE_NAME = :tablename
  AND i.CONSTRAINT_NAME = fc.CONSTRAINT_NAME
  AND fc.CONSTRAINT_SCHEMA = DATABASE()
  AND fc.TABLE_NAME = :tablename
SQL;

        $binded_values = [
          'tablename' => $query_params['tablename'],
        ];

        try {
            $pdo_statement = $pdo->prepare($query);
            $pdo_statement->execute($binded_values);
            $foreign_keys = $pdo_statement->fetchAll();
        } catch (PDOException $e) {
            $payload = [
              'result'  => 'error',
              'message' => $e->getMessage(),
              'code'    => $e->getCode(),
            ];

            return $this->respondWithData($payload);
        }

        $foreign_keys_payload = [];
        if ($foreign_keys && is_array($foreign_keys)) {
            // group by name
            foreach ($foreign_keys as $foreign_key) {
                if (!isset($foreign_keys_payload[$foreign_key['foreign_key_name']])) {
                    $foreign_keys_payload[$foreign_key['foreign_key_name']] = [
                      'name'      => $foreign_key['foreign_key_name'],
                      'on_update' => $foreign_key['on_update'],
                      'on_delete' => $foreign_key['on_delete'],
                    ];
                }
                $foreign_keys_payload[$foreign_key['foreign_key_name']]['columns'][] = [
                  "column_name"     => $foreign_key['column_name'],
                  "ref_table"       => $foreign_key['ref_table'],
                  "ref_column_name" => $foreign_key['ref_column_name'],
                ];
            }

            // remove keys
            $foreign_keys_payload = array_values($foreign_keys_payload);
        }


        $payload['data']   = $foreign_keys_payload;
        $payload['result'] = 'success';

        return $this->respondWithData($payload);
    }
}
