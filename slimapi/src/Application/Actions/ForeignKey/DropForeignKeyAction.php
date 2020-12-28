<?php

declare(strict_types=1);

namespace App\Application\Actions\ForeignKey;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use App\Domain\Driver\Mysql\Mysql;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class DropForeignKeyAction extends Action
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

        $query = "ALTER TABLE ".QueryHelper::escapeMysqlId($query_params['tablename']);
        $query .= " DROP FOREIGN KEY " . QueryHelper::escapeMysqlId($post_params['foreign_key_name']) . ";";

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
