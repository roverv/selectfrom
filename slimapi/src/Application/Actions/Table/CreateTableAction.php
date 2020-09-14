<?php

declare(strict_types=1);

namespace App\Application\Actions\Table;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use App\Domain\Driver\Mysql\Mysql;
use App\Domain\Driver\Mysql\TableStatus;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreateTableAction extends Action
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

        $post_params = (array)$this->request->getParsedBody();

        // @todo: moet ik de onderstaande values binden/escapen??

        $data_type_attributes = $this->mysql_driver->getDataTypeAttributes();
        $table_status         = TableStatus::createFromPost($post_params, $data_type_attributes);

        $query = "CREATE TABLE ".QueryHelper::escapeMysqlId($table_status->getName())." ( ";

        $column_counter = 1;
        foreach ($table_status->getColumns() as $column_index => $column) {
            $query .= QueryHelper::escapeMysqlId($column->getName()).' ';

            $query .= $column->asQueryWithoutName($pdo);

            if ($column_counter < count($table_status->getColumns())) {
                $query .= ', ';
            }

            $column_counter++;
        }

        $auto_increment_column = $table_status->getAutoIncrementColumn();
        if ($auto_increment_column) {
            $query .= ", PRIMARY KEY (".QueryHelper::escapeMysqlId($auto_increment_column->getName()).") ";
        }

        $query .= ") ";

        if (!empty($table_status->getEngine())) {
            $query .= "ENGINE=".$pdo->quote(strtoupper($table_status->getEngine()))." ";
        }

        if (!empty($table_status->getCollation())) {
            $query .= "COLLATE ".$pdo->quote($table_status->getCollation())." ";
        }

        if (!empty($table_status->getComment())) {
            $query .= "COMMENT=".$pdo->quote($table_status->getComment())." ";
        }

        if ($table_status->getAutoIncrementValue() !== null) {
            $query .= "AUTO_INCREMENT=".$table_status->getAutoIncrementValue()." ";
        }

        $query .= ";";

        $result_data          = [];
        $result_data['query'] = $query;

        try {
            $pdo->query($query);
            $result_data['result'] = 'success';
        } catch (Exception $e) {
            return $this->respondWithData(['message' => $e->getMessage()], 500);
        }

        return $this->respondWithData($result_data);
    }
}
