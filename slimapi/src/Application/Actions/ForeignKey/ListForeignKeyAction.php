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
  kcu.CONSTRAINT_NAME AS foreign_key_name,
  kcu.COLUMN_NAME AS column_name,
  kcu.REFERENCED_TABLE_NAME AS reference_table,
  kcu.REFERENCED_COLUMN_NAME AS reference_column_name,
  rc.UPDATE_RULE AS on_update,
  rc.DELETE_RULE AS on_delete
FROM
  information_schema.TABLE_CONSTRAINTS tc
  JOIN information_schema.KEY_COLUMN_USAGE kcu
  JOIN information_schema.REFERENTIAL_CONSTRAINTS rc
WHERE
  tc.CONSTRAINT_TYPE = 'FOREIGN KEY'
  AND tc.TABLE_SCHEMA = DATABASE()
  AND tc.TABLE_NAME = :tablename
  AND tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
  AND kcu.TABLE_SCHEMA = DATABASE()
  AND kcu.TABLE_NAME = :tablename
  AND tc.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
  AND rc.CONSTRAINT_SCHEMA = DATABASE()
  AND rc.TABLE_NAME = :tablename
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
                      'name'            => $foreign_key['foreign_key_name'],
                      'on_update'       => $foreign_key['on_update'],
                      'on_delete'       => $foreign_key['on_delete'],
                      "reference_table" => $foreign_key['reference_table'],
                    ];
                }
                $foreign_keys_payload[$foreign_key['foreign_key_name']]['columns'][] = [
                  "column_name"           => $foreign_key['column_name'],
                  "reference_column_name" => $foreign_key['reference_column_name'],
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
