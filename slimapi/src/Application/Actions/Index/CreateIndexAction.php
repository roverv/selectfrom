<?php

declare(strict_types=1);

namespace App\Application\Actions\Index;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use App\Domain\Driver\Mysql\Mysql;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreateIndexAction extends Action
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
        $pdo          = $this->request->getAttribute('pdo_instance');

        $query_params = $this->request->getQueryParams();
        $post_params  = (array)$this->request->getParsedBody();

        $index_types = $this->mysql_driver->getIndexTypes();
        $index_type = $post_params['type'] ?? '';
        if(!in_array($index_type, $index_types)) {
            $payload = [
              'result'  => 'error',
              'message' => 'Invalid index type',
            ];

            return $this->respondWithData($payload);
        }

        $columns = $post_params['columns'] ?? [];
        $columns_query = array_map(function(array $column) {
            $column_query = QueryHelper::escapeMysqlId($column['name']);
            if((int) $column['length'] > 0) {
                $column_query .= '(' . (int) $column['length'] . ')';
            }
            return $column_query;
        }, $columns);

        $query = "ALTER TABLE ".QueryHelper::escapeMysqlId($query_params['tablename']);
        $query .= " ADD " . (($index_type === 'PRIMARY') ? 'PRIMARY KEY' : $index_type) . " ";
        if(!empty($post_params['name'])) {
            $query .= " " . QueryHelper::escapeMysqlId($post_params['name']) . " ";
        }
        $query .= " (";
        $query .= implode(', ', $columns_query);
        $query .= ");";

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
