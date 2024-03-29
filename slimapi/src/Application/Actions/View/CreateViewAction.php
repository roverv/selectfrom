<?php

declare(strict_types=1);

namespace App\Application\Actions\View;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use App\Domain\Driver\Mysql\Mysql;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreateViewAction extends Action
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

        $query = "CREATE VIEW ".QueryHelper::escapeMysqlId($post_params['view_name']);
        $query .= " AS " . $post_params['view_definition'];
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
