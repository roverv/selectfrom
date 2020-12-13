<?php

declare(strict_types=1);

namespace App\Application\Actions\View;

use App\Application\Actions\Action;
use App\Domain\Driver\Mysql\Mysql;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreationDataViewAction extends Action
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

        $query     = "SELECT TABLE_NAME, VIEW_DEFINITION FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_NAME = ".$pdo->quote(
            $query_params['tablename']
          ).";";
        $view_data = $pdo->query($query)->fetch();

        $return_data = [
          'view_name'       => $view_data['TABLE_NAME'] ?? '',
          'view_definition' => $view_data['VIEW_DEFINITION'] ?? '',
        ];

        return $this->respondWithData($return_data);
    }
}
