<?php

declare(strict_types=1);

namespace App\Domain\Driver\Mysql;

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

}
