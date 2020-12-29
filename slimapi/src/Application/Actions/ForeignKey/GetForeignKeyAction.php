<?php

declare(strict_types=1);

namespace App\Application\Actions\ForeignKey;

use App\Application\Actions\Action;
use App\Domain\Driver\Mysql\Mysql;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class GetForeignKeyAction extends Action
{

    /**
     * @var Mysql
     */
    private $mysql_driver;

    public function __construct(LoggerInterface $logger, Mysql $mysql_driver) {
        parent::__construct($logger);
        $this->mysql_driver = $mysql_driver;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response {
        $pdo = $this->request->getAttribute('pdo_instance');

        $query_params = $this->request->getQueryParams();

        $foreign_key_rules = $this->mysql_driver->getForeignKeyRules();

        $foreign_key_payload = null;
        if (!empty($query_params['foreign_key_name'])) {
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
  AND tc.CONSTRAINT_NAME = :foreignkeyname
  AND tc.TABLE_SCHEMA = DATABASE()
  AND tc.TABLE_NAME = :tablename
  AND kcu.CONSTRAINT_NAME = tc.CONSTRAINT_NAME
  AND kcu.TABLE_SCHEMA = DATABASE()
  AND kcu.TABLE_NAME = :tablename
  AND rc.CONSTRAINT_NAME = tc.CONSTRAINT_NAME
  AND rc.CONSTRAINT_SCHEMA = DATABASE()
  AND rc.TABLE_NAME = :tablename
SQL;

            $binded_values = [
              'tablename'      => $query_params['tablename'],
              'foreignkeyname' => $query_params['foreign_key_name'],
            ];

            try {
                $pdo_statement = $pdo->prepare($query);
                $pdo_statement->execute($binded_values);
                $foreign_key = $pdo_statement->fetchAll();
            } catch (PDOException $e) {
                $payload = [
                  'result'  => 'error',
                  'message' => $e->getMessage(),
                  'code'    => $e->getCode(),
                ];

                return $this->respondWithData($payload);
            }

            $foreign_key_payload = [];
            if ($foreign_key && is_array($foreign_key)) {
                // group by name
                foreach ($foreign_key as $foreign_key_row) {
                    if (!isset($foreign_key_payload[$foreign_key_row['foreign_key_name']])) {
                        $foreign_key_payload[$foreign_key_row['foreign_key_name']] = [
                          'name'            => $foreign_key_row['foreign_key_name'],
                          'on_update'       => $foreign_key_row['on_update'],
                          'on_delete'       => $foreign_key_row['on_delete'],
                          "reference_table" => $foreign_key_row['reference_table'],
                        ];
                    }
                    $foreign_key_payload[$foreign_key_row['foreign_key_name']]['columns'][] = [
                      "column_name"           => $foreign_key_row['column_name'],
                      "reference_column_name" => $foreign_key_row['reference_column_name'],
                    ];
                }
                // theres only one foreign key, take first entry
                $foreign_key_payload = $foreign_key_payload[key($foreign_key_payload)];
            }
        }

        $return_data = [
          'foreign_key'       => $foreign_key_payload,
          'foreign_key_rules' => $foreign_key_rules,
        ];

        return $this->respondWithData($return_data);
    }
}
