<?php

declare(strict_types=1);

namespace App\Application\Actions\Row;

use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use Psr\Http\Message\ResponseInterface as Response;

class ListRowAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo          = $this->request->getAttribute('pdo_instance');
        $query_params = $this->request->getQueryParams();

        $table_data = [];

        //  $rows = $pdo->query("SELECT * FROM " . $_GET['tablename']. " LIMIT 30")->fetchAll();
        // no need to get columns if we are retrieving more rows, they are already retrieved on the first call
        if (empty($query_params['offset'])) {
            $query                 = "SHOW FULL COLUMNS FROM ".QueryHelper::escapeMysqlId($query_params['tablename']);
            $rows                  = $pdo->query($query)->fetchAll();
            $table_data['columns'] = $rows;
        }

        $amount_rows_per_page = 30; //todo: instelbaar maken

        $query = "SELECT * FROM ".QueryHelper::escapeMysqlId($query_params['tablename'])." ";

        $filter_query = '';
        if (!empty($query_params['column']) && !empty($query_params['value']) && !empty($query_params['comparetype'])) {
            //todo: escape, duh
            if ($query_params['column'] == 'primarykey') {
                $primary_key_column = array_filter(
                  $table_data['columns'],
                  function ($column) {
                      return ($column['Key'] === 'PRI');
                  }
                );
                if (count($primary_key_column) == 0) {
                    return $this->respondWithData(
                      [
                        'result'  => 'error',
                        'message' => 'Table has no PRIMARY KEY',
                      ]
                    ); //todo: this is not yet processed on front end side
                }
                $filter_query .= "WHERE `".$primary_key_column[0]['Field']."` = '".$query_params['value']."' ";
            } elseif ($query_params['comparetype'] == 'is') {
                $filter_query .= "WHERE `".$query_params['column']."` = '".$query_params['value']."' ";
            } elseif ($query_params['comparetype'] == 'like') {
                $filter_query .= "WHERE `".$query_params['column']."` LIKE '%".$query_params['value']."%' ";
            }
        }

        $query .= $filter_query;

        if (!empty($query_params['orderby']) && !empty($query_params['orderdirection'])) {
            $query .= "ORDER BY `".$query_params['orderby']."` ".$query_params['orderdirection']." ";
        }

        if (!empty($query_params['column']) && $query_params['column'] == 'primarykey') {
        } elseif (!empty($query_params['limit']) && $query_params['limit'] == 'none') {
        } else {
            $query .= " LIMIT ".(int)$amount_rows_per_page." ";
        }

        if (!empty($query_params['offset'])) {
            $offset = $amount_rows_per_page * (int)$query_params['offset'];
            $query  .= " OFFSET ".$offset." ";
        }

        $count_query  = "SELECT COUNT(*) as amount_rows FROM ".QueryHelper::escapeMysqlId($query_params['tablename']);
        $count_query  .= " ".$filter_query;
        $count_result = $pdo->query($count_query)->fetch();

        $table_data['amount_rows'] = $count_result['amount_rows'] ?? 0;

        $rows               = $pdo->query($query)->fetchAll();
        $table_data['data'] = $rows;

        return $this->respondWithData($table_data);
    }
}
