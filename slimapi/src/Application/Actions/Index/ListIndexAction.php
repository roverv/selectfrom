<?php

declare(strict_types=1);

namespace App\Application\Actions\Index;


use App\Application\Actions\Action;
use App\Application\Helpers\QueryHelper;
use Psr\Http\Message\ResponseInterface as Response;
class ListIndexAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $pdo          = $this->request->getAttribute('pdo_instance');
        $query_params = $this->request->getQueryParams();

        $payload = [];

        try {
            $query              = "SHOW INDEXES FROM ".QueryHelper::escapeMysqlId($query_params['tablename']);
            $indexes            = $pdo->query($query)->fetchAll();
        } catch (PDOException $e) {
            $payload = [
              'result'  => 'error',
              'message' => $e->getMessage(),
              'code'    => $e->getCode(),
            ];

            return $this->respondWithData($payload);
        }

        $indexes_payload = [];
        foreach ($indexes as $index_row) {
            $index_name                           = $index_row['Key_name'];
            $indexes_payload[$index_name]['name'] = $index_name;
            $indexes_payload[$index_name]['is_unique'] = ((int) $index_row["Non_unique"] === 0) ? 1 : 0;

            /**
             * Table 13.2 InnoDB Storage Engine Index Characteristics
             * https://dev.mysql.com/doc/refman/8.0/en/create-index.html
             * Index Class  Index Type  Stores NULL VALUES  Permits Multiple NULL Values  IS NULL Scan Type  IS NOT NULL Scan Type
             * Primary key  BTREE  No  No  N/A  N/A
             * Unique  BTREE  Yes  Yes  Index  Index
             * Key  BTREE  Yes  Yes  Index  Index
             * FULLTEXT  N/A  Yes  Yes  Table  Table
             * SPATIAL  N/A  No  No  N/A  N/A
             */
            if ($index_name === 'PRIMARY') {
                $indexes_payload[$index_name]["type"] = 'PRIMARY';
            } elseif ($index_row['Index_type'] === 'FULLTEXT') {
                $indexes_payload[$index_name]["type"] = 'FULLTEXT';
            } elseif ((int) $index_row["Non_unique"] === 0) {
                $indexes_payload[$index_name]["type"] = 'UNIQUE';
            } elseif ($index_row["Index_type"] === 'SPATIAL') {
                $indexes_payload[$index_name]["type"] = 'SPATIAL';
            } else {
                $indexes_payload[$index_name]["type"] = 'INDEX';
            }

            $indexes_payload[$index_name]["columns"][] = [
              'name'   => $index_row["Column_name"],
              'length' => $index_row["Sub_part"] ?? null,
            ];
        }

        // remove keys
        $indexes_payload = array_values($indexes_payload);

        $payload['data'] = $indexes_payload;
        $payload['result'] = 'success';

        return $this->respondWithData($payload);
    }
}
