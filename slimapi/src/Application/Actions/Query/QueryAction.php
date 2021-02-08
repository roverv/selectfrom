<?php

declare(strict_types=1);

namespace App\Application\Actions\Query;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;

class QueryAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        // @todo: memory limit and timelimit set
//        ini_set('memory_limit', '5120M');
//        set_time_limit (0);

        $pdo = $this->request->getAttribute('pdo_instance');

        // Get all POST parameters
        $post_params = (array)$this->request->getParsedBody();

        $sql_text = $post_params['query'];

        $refresh_data_on_query_types = [
          'CREATE TABLE',
          'ALTER TABLE',
          'DROP TABLE',
        ];

        $queries  = QueryHelper::splitSql($sql_text);

        $return_data = [
          'query_results' => [],
          'refresh_cache' => false,
        ];
        foreach ($queries as $query) {
            if (empty(trim($query))) {
                continue;
            }

            $result_data = ['query' => $query];
            try {
                $execution_start               = microtime(true);
                $query_result                  = $pdo->query($query);
                $execution_end                 = microtime(true);
                $result_data['execution_time'] = $execution_end - $execution_start;
            } catch (PDOException $e) {
                $result_data['result']          = 'error';
                $result_data['message']         = $e->getMessage();
                $return_data['query_results'][] = $result_data;
                continue;
            }

            $result_data['result'] = 'success';

            if ($return_data['refresh_cache'] === false) {
                $query_type = QueryHelper::getQueryType($query);
                if (in_array($query_type, $refresh_data_on_query_types)) {
                    $return_data['refresh_cache'] = true;
                }
            }


            // if the result has columns, it means it has data (eg SELECT or EXPLAIN query)
            if ($query_result->columnCount()) {
                $columns_meta = [];
                foreach (range(0, $query_result->columnCount() - 1) as $column_index) {
                    $columns_meta[] = $query_result->getColumnMeta($column_index);
                }
                $result_data['columns_meta'] = $columns_meta;
                // fetch as num, because of possible joins with the same column names
                $result_data['rows'] = $query_result->fetchAll(\PDO::FETCH_NUM);
                $result_data['row_count']    = $query_result->rowCount();
                $result_data['type']         = 'data';
            } else {
                // DML (data manipulation language) queries
                $result_data['affected_rows'] = $query_result->rowCount();
                $result_data['type']          = 'change';
            }
            $return_data['query_results'][] = $result_data;
        }

        return $this->respondWithData($return_data);
    }
}
