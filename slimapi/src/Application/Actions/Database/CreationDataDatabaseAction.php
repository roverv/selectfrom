<?php

declare(strict_types=1);

namespace App\Application\Actions\Database;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use App\Domain\Driver\Mysql\Mysql;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreationDataDatabaseAction extends Action
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

        $collations  = $this->mysql_driver->getCollations($pdo);
        $return_data = [
          'collations' => $collations,
        ];

        if (!empty($query_params['db'])) {
            $query                    = "SELECT @@collation_database as database_collation;";
            $database_collation       = $pdo->query($query)->fetch();
            $return_data['collation'] = $database_collation['database_collation'];
        }

        return $this->respondWithData($return_data);
    }
}
