<?php

declare(strict_types=1);

namespace App\Application\Actions\Table;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use App\Domain\Driver\Mysql\ColumnStructure;
use App\Domain\Driver\Mysql\Mysql;
use App\Domain\Driver\Mysql\TableStatus;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class AlterTableAction extends Action
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
        $pdo = $this->request->getAttribute('pdo_instance');

        $query_params = $this->request->getQueryParams();
        $post_params  = (array)$this->request->getParsedBody();

        // get current table data
        $data_type_attributes = $this->mysql_driver->getDataTypeAttributes();
        $query = "SHOW TABLE STATUS WHERE name = '".$query_params['tablename']."';";
        $table_status_data    = $pdo->query($query)->fetch();

        $query = "SHOW FULL COLUMNS FROM ".QueryHelper::escapeMysqlId($_GET['tablename']).";";
        $columns_data         = $pdo->query($query)->fetchAll();

        $table_indexes = $pdo->query("SHOW INDEX FROM " . QueryHelper::escapeMysqlId($_GET['tablename']))->fetchAll();

        $original_table_status = TableStatus::createFromDatabase(
          $table_status_data,
          $columns_data,
          $data_type_attributes
        );

        $new_table_status = TableStatus::createFromPost($post_params, $data_type_attributes);

        $query = "ALTER TABLE ".QueryHelper::escapeMysqlId($original_table_status->getName())." ";

        $changed_columns = array_filter(
          $new_table_status->getColumns(),
          function (ColumnStructure $column) use ($pdo, $original_table_status, $table_indexes) {
              // new column, skip
              if (empty($column->getOriginalFieldName())) {
                  return false;
              }
              // name of column has changed
              if ($column->getName() !== $column->getOriginalFieldName()) {
                  return true;
              }
              // position of column has changed
              if (!empty($column->getAfterColumn())) {
                  return true;
              }

              $original_column = $original_table_status->getColumnByName($column->getOriginalFieldName());

              if ($original_column) {
                  $old_structure = $original_column->asQueryWithoutName($pdo, $table_indexes);
                  $new_structure = $column->asQueryWithoutName($pdo, $table_indexes);

                  // structure has changed
                  if ($old_structure != $new_structure) {
                      return true;
                  }
              }

              return false;
          }
        );


        $new_columns = array_filter(
          $new_table_status->getColumns(),
          function (ColumnStructure $column) {
              // name of column has changed
              if (empty($column->getOriginalFieldName())) {
                  return true;
              }

              return false;
          }
        );

        $current_columns = array_map(
          function (ColumnStructure $column) {
              return $column->getOriginalFieldName();
          },
          $new_table_status->getColumns()
        );

        $deleted_columns = array_filter(
          $original_table_status->getColumns(),
          function (ColumnStructure $original_column) use ($current_columns) {
              if (!in_array($original_column->getName(), $current_columns)) {
                  return true;
              }

              return false;
          }
        );

        array_walk(
          $new_columns,
          function (ColumnStructure $new_column) use (&$query, $pdo, $table_indexes) {
              $query .= 'ADD '.QueryHelper::escapeMysqlId($new_column->getName()).' '.$new_column->asQueryWithoutName(
                  $pdo, $table_indexes
                ).", ";
          }
        );

        array_walk(
          $deleted_columns,
          function (ColumnStructure $deleted_column) use (&$query, $pdo) {
              $query .= 'DROP '.QueryHelper::escapeMysqlId($deleted_column->getName()).', ';
          }
        );

        array_walk(
          $changed_columns,
          function (ColumnStructure $changed_column) use (&$query, $pdo, $table_indexes) {
              $query .= 'CHANGE '.QueryHelper::escapeMysqlId($changed_column->getOriginalFieldName()).' ';
              $query .= QueryHelper::escapeMysqlId($changed_column->getName()).' ';
              $query .= $changed_column->asQueryWithoutName($pdo, $table_indexes).', ';
          }
        );

        if ($original_table_status->getName() != $new_table_status->getName()) {
            $query .= 'RENAME TO '.QueryHelper::escapeMysqlId($new_table_status->getName()).', ';
        }

        if ($original_table_status->getEngine() != $new_table_status->getEngine()) {
            $query .= "ENGINE=".$pdo->quote(strtoupper($new_table_status->getEngine()))." ";
        }

        if ($original_table_status->getCollation() != $new_table_status->getCollation()) {
            $query .= "COLLATE ".$pdo->quote($new_table_status->getCollation())." ";
        }

        if ($original_table_status->getComment() != $new_table_status->getComment()) {
            $query .= "COMMENT=".$pdo->quote($new_table_status->getComment())." ";
        }

        if ($original_table_status->getAutoIncrementValue() !== $new_table_status->getAutoIncrementValue()) {
            $query .= "AUTO_INCREMENT=".$new_table_status->getAutoIncrementValue()." ";
        }

        if (substr($query, -2) == ', ') {
            $query = substr($query, 0, -2);
        }

        if (substr($query, -1) == ' ') {
            $query = substr($query, 0, -1);
        }

        $query .= ";";

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
