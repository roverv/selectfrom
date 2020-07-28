<?php

    function getDataTypeAttributes() {
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
