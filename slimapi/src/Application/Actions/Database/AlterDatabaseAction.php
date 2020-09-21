<?php

declare(strict_types=1);

namespace App\Application\Actions\Database;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use App\Domain\Driver\Mysql\Mysql;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class AlterDatabaseAction extends Action
{
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
        $pdo          = $this->request->getAttribute('pdo_instance');
        $post_params  = (array)$this->request->getParsedBody();
        $query_params = $this->request->getQueryParams();

        $query                = "SELECT @@collation_database as database_collation;";
        $database_collation   = $pdo->query($query)->fetch();
        $current_db_collation = $database_collation['database_collation'];
        $current_db_name      = $query_params['db'];

        $new_db_collation = trim($post_params['collation']);
        $new_db_name      = trim($post_params['database_name']);

        $result_data['query'] = '';

        // if the name has changed, we need to: create a new database, move the tables to the new database (by renaming)
        // drop the old database, see: https://stackoverflow.com/questions/67093/how-do-i-quickly-rename-a-mysql-database-change-schema-name
        if ($new_db_name !== $current_db_name) {
            $create_query = "CREATE DATABASE ".QueryHelper::escapeMysqlId($new_db_name);
            if (!empty($new_db_collation)) {
                $create_query .= " COLLATE ".$pdo->quote($new_db_collation).';';
            }

            $table_list_query = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME";

            try {
                $table_list         = $pdo->query($table_list_query)->fetchAll();
                $table_rename_query = false;
                if ($table_list && is_array($table_list) && count($table_list) > 0) {
                    $table_rename_query = 'RENAME TABLE ';
                    $tables_rename      = array_map(
                      function ($table_data) use ($current_db_name, $new_db_name, $pdo) {
                          $rename_query = QueryHelper::escapeMysqlId($current_db_name).'.'.QueryHelper::escapeMysqlId($table_data['TABLE_NAME']);
                          $rename_query .= ' TO ';
                          $rename_query .= QueryHelper::escapeMysqlId($new_db_name).'.'.QueryHelper::escapeMysqlId($table_data['TABLE_NAME']);

                          return $rename_query;
                      },
                      $table_list
                    );
                    $table_rename_query .= implode(', ', $tables_rename).';';
                }

                $drop_old_table_query = 'DROP DATABASE '.QueryHelper::escapeMysqlId($current_db_name).';';

                $pdo->query($create_query);
                if ($table_rename_query !== false) {
                    $pdo->query($table_rename_query);
                }
                $pdo->query($drop_old_table_query);

                $result_data['result'] = 'success';
                $result_data['query']  = $create_query;
                if ($table_rename_query !== false) {
                    $result_data['query'] .= $table_rename_query;
                }
                $result_data['query'] .= $drop_old_table_query;
            } catch (\PDOException $e) {
                $payload = [
                  'result'  => 'error',
                  'message' => $e->getMessage(),
                  'code'    => $e->getCode(),
                ];

                return $this->respondWithData($payload);
            }

        } elseif ($current_db_collation !== $new_db_collation) {
            $alter_query = "ALTER DATABASE ".QueryHelper::escapeMysqlId($current_db_name);
            $alter_query .= ' COLLATE ' . $pdo->quote($post_params['collation']) . ';';
            try {
                $pdo->query($alter_query);
                $result_data['result'] = 'success';
                $result_data['query'] = $alter_query;
            } catch (\PDOException $e) {
                $payload = [
                  'result'  => 'error',
                  'message' => $e->getMessage(),
                  'code'    => $e->getCode(),
                ];

                return $this->respondWithData($payload);
            }
        }

        // nothing changed
        $result_data['result'] = 'success';

        return $this->respondWithData($result_data);
    }
}
