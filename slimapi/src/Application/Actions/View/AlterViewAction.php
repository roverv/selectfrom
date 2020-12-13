<?php

declare(strict_types=1);

namespace App\Application\Actions\View;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use App\Domain\Driver\Mysql\Mysql;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class AlterViewAction extends Action
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

         // view name is not changed
        if($query_params['tablename'] == $post_params['view_name']) {
            $query = "CREATE OR REPLACE VIEW ".QueryHelper::escapeMysqlId($query_params['tablename']);
            $query .= " AS " . $post_params['view_definition'];
        }
        else {
            $query = "CREATE VIEW ".QueryHelper::escapeMysqlId($post_params['view_name']);
            $query .= " AS " . $post_params['view_definition'];
            $drop_query = "DROP VIEW " . QueryHelper::escapeMysqlId($query_params['tablename']) . ";";
        }

        $query .= ";";

        $result_data          = [];
        $result_data['query'] = $query;

        try {
            $pdo->query($query);
            if(isset($drop_query)) {
                $pdo->query($drop_query);
                $result_data['query'] .= $drop_query;
            }
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
