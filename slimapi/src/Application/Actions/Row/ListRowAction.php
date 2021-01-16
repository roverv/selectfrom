<?php

declare(strict_types=1);

namespace App\Application\Actions\Row;

use App\Application\Actions\Action;
use App\Application\Helpers\PdoHelper;
use App\Application\Helpers\QueryHelper;
use PDO;
use Psr\Http\Message\ResponseInterface as Response;

class ListRowAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo          = $this->request->getAttribute('pdo_instance');
        $query_params = $this->request->getQueryParams();

        $table_data = [];

        // no need to get columns if we are retrieving more rows, they are already retrieved on the first call
        if (empty($query_params['offset'])) {

            $foreign_keys_query = <<<SQL
SELECT
  kcu.CONSTRAINT_NAME AS foreign_key_name,
  kcu.COLUMN_NAME AS column_name,
  kcu.REFERENCED_TABLE_NAME AS reference_table,
  kcu.REFERENCED_COLUMN_NAME AS reference_column_name
FROM
  information_schema.TABLE_CONSTRAINTS tc
  JOIN information_schema.KEY_COLUMN_USAGE kcu
WHERE
  tc.CONSTRAINT_TYPE = 'FOREIGN KEY'
  AND tc.TABLE_SCHEMA = DATABASE()
  AND tc.TABLE_NAME = :tablename
  AND tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
  AND kcu.TABLE_SCHEMA = DATABASE()
  AND kcu.TABLE_NAME = :tablename
SQL;

            $binded_values = [
              'tablename' => $query_params['tablename'],
            ];

            try {
                $query = "SHOW FULL COLUMNS FROM ".QueryHelper::escapeMysqlId($query_params['tablename']);
                $rows                  = $pdo->query($query)->fetchAll();
                $table_data['columns'] = $rows;

                $pdo_statement = $pdo->prepare($foreign_keys_query);
                $pdo_statement->execute($binded_values);
                $table_data['foreign_keys'] = $pdo_statement->fetchAll();
            } catch (\PDOException $e) {
                $payload = [
                  'result'  => 'error',
                  'message' => $e->getMessage(),
                  'code'    => $e->getCode(),
                ];
                return $this->respondWithData($payload);
            }
        }

        $amount_rows_per_page = (!empty($query_params['limit'])) ? (int) $query_params['limit'] : 0;

        $query = "SELECT * FROM ".QueryHelper::escapeMysqlId($query_params['tablename'])." ";

        $filter_query = '';
        $binded_values = [];
        if (!empty($query_params['column']) && !empty($query_params['value']) && !empty($query_params['comparetype'])) {
            if ($query_params['column'] == 'primarykey') {
                $primary_key_column = array_filter(
                  $table_data['columns'],
                  function ($column) {
                      return ($column['Key'] === 'PRI');
                  }
                );
                if (count($primary_key_column) == 0) {
                    return $this->respondWithData(
                      [
                        'result'  => 'error',
                        'message' => 'Table has no PRIMARY KEY',
                      ]
                    ); //todo: this is not yet processed on front end side
                }
                $filter_query .= "WHERE ".QueryHelper::escapeMysqlId($primary_key_column[0]['Field'])." = :primarykeyvalue ";
                $binded_values['primarykeyvalue'] = $query_params['value'];
            } elseif ($query_params['comparetype'] == 'is') {
                $filter_query .= "WHERE ".QueryHelper::escapeMysqlId($query_params['column'])." = :isvalue ";
                $binded_values['isvalue'] = $query_params['value'];
            } elseif ($query_params['comparetype'] == 'like') {
                $filter_query .= "WHERE ".QueryHelper::escapeMysqlId($query_params['column'])." LIKE :likevalue ";
                $binded_values['likevalue'] = "%".$query_params['value']."%";
            }
        }

        $query .= $filter_query;

        if (!empty($query_params['orderby']) && !empty($query_params['orderdirection'])) {
            $order_by_direction = (strtolower($query_params['orderdirection']) === 'desc') ? 'DESC' : 'ASC';
            $query .= "ORDER BY ".QueryHelper::escapeMysqlId($query_params['orderby'])." ".$order_by_direction." ";
        }

        if ($amount_rows_per_page > 0) {
            $query .= " LIMIT :limit ";
            $limit_bind = (int) $amount_rows_per_page;
        }

        if (!empty($query_params['offset'])) {
            $query  .= " OFFSET :offset ";
            $offset_bind = (int)$amount_rows_per_page * (int)$query_params['offset'];
        }

        try {
            $count_query  = "SELECT COUNT(*) as amount_rows FROM ".QueryHelper::escapeMysqlId($query_params['tablename']);
            $count_query  .= " ".$filter_query;
            $pdo_statement = $pdo->prepare($count_query);
            $pdo_statement->execute($binded_values);
            $table_data['amount_rows'] = $pdo_statement->fetchColumn();
        } catch (\PDOException $e) {
            $payload = [
              'result'  => 'error',
              'message' => $e->getMessage(),
              'code'    => $e->getCode(),
            ];

            return $this->respondWithData($payload);
        }

        $pdo_statement         = $pdo->prepare($query);
        $emulated_query_string = $pdo_statement->queryString;

        try {

            if($amount_rows_per_page > 0) {
                // we have to bind these seperately as int, see: https://phpdelusions.net/pdo#limit
                $pdo_statement->bindValue(':limit', (int) $limit_bind, PDO::PARAM_INT);
            }
            if (!empty($query_params['offset'])) {
                $pdo_statement->bindValue(':offset', (int) $offset_bind, PDO::PARAM_INT);
            }
            foreach($binded_values as $value_key => $value) {
                $pdo_statement->bindValue(':' . $value_key, $value);
            }
            $pdo_statement->execute();
            $table_data['data'] = $pdo_statement->fetchAll();
        } catch (\PDOException $e) {
            $payload = [
              'result'  => 'error',
              'message' => $e->getMessage(),
              'code'    => $e->getCode(),
            ];

            return $this->respondWithData($payload);
        }

        $prepare_values = $binded_values;
        if($amount_rows_per_page > 0) {
            $prepare_values['limit'] = $limit_bind;
        }
        if (!empty($query_params['offset'])) {
            $prepare_values['offset'] = $offset_bind;
        }
        $emulated_query = PdoHelper::emulateSqlString($emulated_query_string, $prepare_values);

        $table_data['result'] = 'success';
        $table_data['query'] = $emulated_query;

        return $this->respondWithData($table_data);
    }
}
