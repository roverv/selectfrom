<?php

declare(strict_types=1);

namespace App\Domain\Driver\Mysql;

use PDO;

class Mysql
{
    public function getDataTypeAttributes()
    {
        return [
          'tinyint'    => 'numeric',
          'smallint'   => 'numeric',
          'mediumint'  => 'numeric',
          'int'        => 'numeric',
          'bigint'     => 'numeric',
          'float'      => 'numeric',
          'double'     => 'numeric',
          'decimal'    => 'numeric',
          'char'       => 'collation',
          'varchar'    => 'collation',
          'tinytext'   => 'collation',
          'text'       => 'collation',
          'mediumtext' => 'collation',
          'longtext'   => 'collation',
          'enum'       => 'collation',
          'set'        => 'collation',
        ];
    }

    public function getDataTypes()
    {
        return [
          'Numeric'       => ['tinyint', 'smallint', 'mediumint', 'int', 'bigint', 'float', 'double', 'decimal', 'bit'],
          'Date and time' => ['date', 'time', 'datetime', 'timestamp', 'year'],
          'String'        => [
            'char',
            'varchar',
            'binary',
            'varbinary',
            'tinyblob',
            'blob',
            'mediumblob',
            'longblob',
            'tinytext',
            'text',
            'mediumtext',
            'longtext',
            'enum',
            'set',
          ],
          'JSON'          => ['JSON'],
        ];
    }

    public function getCollations(PDO $pdo) {
        $collations = $pdo->query("SHOW COLLATION")->fetchAll();

        // sort by the ones which are default, first
        uasort(
          $collations,
          function ($a, $b) {
              return $a['Collation'] <=> $b['Collation'];
          }
        );

        // group by charset, only get the names
        $collations = array_reduce(
          $collations,
          function ($collations_grouped, $collation) {
              $collations_grouped[$collation['Charset']][] = $collation['Collation'];

              return $collations_grouped;
          }
        );

        // sort alphabetically by key
        ksort($collations);

        return $collations;
    }

    public function getIndexTypes() {
        return [
          'PRIMARY',
          'INDEX',
          'UNIQUE',
          'FULLTEXT',
          'SPATIAL',
        ];
    }

}
