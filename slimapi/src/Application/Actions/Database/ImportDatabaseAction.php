<?php

declare(strict_types=1);

namespace App\Application\Actions\Database;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\NonBufferedBody;

class ImportDatabaseAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        $pdo = $this->request->getAttribute('pdo_instance');

        $files       = $this->request->getUploadedFiles();

        $sql_text = '';
        // handle single input with single file upload
        $uploadedFile = $files['import_file'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $file_extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
            if($file_extension !== 'sql' && $file_extension !== 'gz') {
                $payload = [
                  'result'  => 'error',
                  'message' => 'Uploaded file should be a SQL file (.sql) or GZIP compressed SQL file (.gz)'
                ];
                return $this->respondWithData($payload);
            }

            // @todo: check size of file and verify with php server limit

            $local_file_path = $uploadedFile->getFilePath();
            try {
                $contents = file_get_contents($local_file_path);
                if($file_extension === 'gz') {
                    $contents = gzdecode($contents);
                }
                $sql_text = $contents;
            }
            catch (\Exception $exception) {
                $payload = [
                  'result'  => 'error',
                  'message' => sprintf('Uploaded file could not be read. Is this a valid .%s file?', $file_extension),
                  'code'    => $exception->getCode(),
                ];
                return $this->respondWithData($payload);
            }
        }
        else {
            $response_json = [
              'result'  => 'error',
              'message' => 'Uploaded file could not be processed',
            ];
            return $this->respondWithData($response_json);
        }

        $refresh_data_on_query_types = [
          'CREATE TABLE',
          'ALTER TABLE',
          'DROP TABLE',
        ];

        $sql_text = QueryHelper::removeComments($sql_text);
        $queries  = QueryHelper::splitSql($sql_text);

        //        return $this->respondWithData([$queries]);


        //      https://discourse.slimframework.com/t/implementing-server-sent-events-with-slim-4/4482/5
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        $response = $this->response
          ->withBody(new NonBufferedBody())
          ->withHeader('Content-Type', 'application/json')
          ->withHeader('Access-Control-Allow-Credentials', 'true')
          ->withHeader('Access-Control-Allow-Origin', $origin)
          ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, X-CSRF-NAME, X-CSRF-VALUE')
          ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
          ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
          ->withAddedHeader('Cache-Control', 'post-check=0, pre-check=0')
          ->withHeader('Pragma', 'no-cache');

        $body = $response->getBody();

        $query_progress = [
          'total_queries' => count($queries),
          'queries_executed' => 0
        ];
        $body->write(json_encode([$query_progress]));

        $return_data = [
          'query_results' => [],
          'refresh_cache' => false,
        ];

        $start_time = microtime(true);
        $passed_time_step = 0;
        foreach ($queries as $query) {

            $query_progress['queries_executed']++;

            // time passed in microseconds
            $time_passed = (microtime(true) - $start_time) * 100;
            // only output every 0.1 sec (10 microseconds)
            if(floor($time_passed / 10) > $passed_time_step) {
                $body->write(json_encode([$query_progress]));
                $passed_time_step = floor($time_passed / 10);
            }

            if (empty(trim($query))) {
                continue;
            }

            $result_data = [];
            try {
                $execution_start               = microtime(true);
                $query_result                  = $pdo->query($query);
                $execution_end                 = microtime(true);
                $result_data['execution_time'] = $execution_end - $execution_start;
            } catch (PDOException $e) {
                $result_data['query']          = $query;
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
                $result_data['type']         = 'data';
            } else {
                // DML (data manipulation language) queries
                $result_data['affected_rows'] = $query_result->rowCount();
                $result_data['type']          = 'change';
            }
            $return_data['query_results'][] = $result_data;
        }

        $body->write(json_encode($return_data));

        return $response;
    }
}
