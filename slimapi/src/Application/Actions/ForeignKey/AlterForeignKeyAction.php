<?php

declare(strict_types=1);

namespace App\Application\Actions\ForeignKey;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use App\Domain\Driver\Mysql\Mysql;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class AlterForeignKeyAction extends Action
{

    /**
     * @var Mysql
     */
    private $mysql_driver;

    public function __construct(LoggerInterface $logger, Mysql $mysql_driver)
    {
        parent::__construct($logger);
        $this->mysql_driver = $mysql_driver;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo = $this->request->getAttribute('pdo_instance');

        $query_params = $this->request->getQueryParams();
        $post_params  = (array)$this->request->getParsedBody();

        $foreign_key_rules = $this->mysql_driver->getForeignKeyRules();
        $on_update_rule    = $post_params['on_update'] ?? '';
        $on_delete_rule    = $post_params['on_delete'] ?? '';
        if (!in_array($on_update_rule, $foreign_key_rules) || !in_array($on_delete_rule, $foreign_key_rules)) {
            $payload = [
              'result'  => 'error',
              'message' => 'Invalid foreign key rule',
            ];

            return $this->respondWithData($payload);
        }

        $columns       = $post_params['columns'] ?? [];
        $columns_query = array_map(function (array $column) {
            $column_query = QueryHelper::escapeMysqlId($column['column_name']);

            return $column_query;
        }, $columns);

        $reference_columns       = $post_params['columns'] ?? [];
        $reference_columns_query = array_map(function (array $column) {
            $column_query = QueryHelper::escapeMysqlId($column['reference_column_name']);

            return $column_query;
        }, $reference_columns);

        //        ALTER TABLE `actor` ADD FOREIGN KEY(`actor_id`) REFERENCES `actor`(`actor_id`) ON DELETE CASCADE ON UPDATE CASCADE;
        //        ALTER TABLE `actor` ADD CONSTRAINT `123` FOREIGN KEY (`actor_id`) REFERENCES `actor`(`actor_id`) ON DELETE CASCADE ON UPDATE CASCADE;

        $query = "ALTER TABLE ".QueryHelper::escapeMysqlId($query_params['tablename']);
        $query .= " DROP FOREIGN KEY " . QueryHelper::escapeMysqlId($query_params['foreign_key_name']) . ",";
        $query .= " ADD";
        if (!empty($post_params['name'])) {
            $query .= " CONSTRAINT ".QueryHelper::escapeMysqlId($post_params['name'])." ";
        }
        $query .= " FOREIGN KEY ";
        $query .= "(";
        $query .= implode(', ', $columns_query);
        $query .= ")";
        $query .= " REFERENCES";
        $query .= " ".QueryHelper::escapeMysqlId($post_params['reference_table']) . " ";
        $query .= "(";
        $query .= implode(', ', $reference_columns_query);
        $query .= ") ";
        $query .= "ON DELETE " . $on_delete_rule . " ";
        $query .= "ON UPDATE " . $on_update_rule . ";";
        //        return $this->respondWithData($query);
        $result_data          = [];
        $result_data['query'] = $query;

        try {
            $pdo->query($query);
            $result_data['result'] = 'success';
        } catch (\PDOException $e) {
            $payload = [
              'result'  => 'error',
              'message' => $e->getMessage(),
              'code'    => $e->getCode(),
            ];

            return $this->respondWithData($payload);
        }

        return $this->respondWithData($result_data);
    }
}
