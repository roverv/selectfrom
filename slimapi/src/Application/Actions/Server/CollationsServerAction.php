<?php

declare(strict_types=1);

namespace App\Application\Actions\Server;

use App\Application\Actions\Action;
use App\Domain\Driver\Mysql\Mysql;
use Psr\Http\Message\ResponseInterface as Response;

class CollationsServerAction extends Action
{

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo = $this->request->getAttribute('pdo_instance');

        $mysql_driver = new Mysql();
        $collations   = $mysql_driver->getCollations($pdo);
        $return_data  = [
          'collations' => $collations,
        ];

        return $this->respondWithData($return_data);
    }
}
