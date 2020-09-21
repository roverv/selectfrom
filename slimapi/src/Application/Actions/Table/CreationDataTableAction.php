<?php

declare(strict_types=1);

namespace App\Application\Actions\Table;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use App\Domain\Driver\Mysql\Mysql;
use App\Domain\Driver\Mysql\TableStatus;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreationDataTableAction extends Action
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

        $collations = $this->mysql_driver->getCollations($pdo);

        $engines = $pdo->query("SHOW ENGINES")->fetchAll();
        // filter out not supported engines
        $engines = array_filter(
          $engines,
          function ($engine) {
              return ($engine['Support'] == 'DEFAULT' || $engine['Support'] == 'YES');
          }
        );
        // we only need the names
        $engines = array_map(
          function ($engine) {
              return $engine['Engine'];
          },
          $engines
        );

        $data_types           = $this->mysql_driver->getDataTypes();
        $data_type_attributes = $this->mysql_driver->getDataTypeAttributes();

        if (!empty($query_params['tablename'])) {
            $query             = "SHOW TABLE STATUS WHERE name = '".$query_params['tablename']."';";
            $table_status_data = $pdo->query($query)->fetch();

            $query        = "SHOW FULL COLUMNS FROM ".QueryHelper::escapeMysqlId($query_params['tablename']).";";
            $columns_data = $pdo->query($query)->fetchAll();

            $table_data = TableStatus::createFromDatabase(
              $table_status_data,
              $columns_data,
              $data_type_attributes
            );
        }

        $return_data = [
          'collations'           => $collations,
          'engines'              => $engines,
          'data_types'           => $data_types,
          'data_type_attributes' => $data_type_attributes,
        ];

        if (isset($table_data)) {
            $return_data['table_data'] = $table_data;
        }

        return $this->respondWithData($return_data);
    }
}
