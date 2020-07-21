<?php

    require_once '_header.php';

    $collations = $pdo->query("SHOW COLLATION")->fetchAll();

    // sort by the ones which are default, first
    uasort($collations, function ($a, $b) {
        return $a['Collation'] <=> $b['Collation'];
    });

    // group by charset, only get the names
    $collations = array_reduce($collations, function ($collations_grouped, $collation) {
        $collations_grouped[$collation['Charset']][] = $collation['Collation'];

        return $collations_grouped;
    });

    // sort alphabetically by key
    ksort($collations);

    $engines = $pdo->query("SHOW ENGINES")->fetchAll();
    // filter out not supported engines
    $engines = array_filter($engines, function ($engine) {
        return ($engine['Support'] == 'DEFAULT' || $engine['Support'] == 'YES');
    });
    // we only need the names
    $engines = array_map(function ($engine) {
        return $engine['Engine'];
    }, $engines);

    $data_types = [
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

    $data_type_attributes = [
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

    if (!empty($_GET['tablename'])) {

        $table_status = $pdo->query("SHOW TABLE STATUS WHERE name = '" . $_GET['tablename'] . "';")->fetch();

        $table_data                        = [];
        $table_data['status']['name']      = $table_status['Name'];
        $table_data['status']['engine']    = $table_status['Engine'];
        $table_data['status']['collation'] = $table_status['Collation'];
        $table_data['status']['comment']   = $table_status['Comment'];

        $columns               = $pdo->query("SHOW FULL COLUMNS FROM " . escape_mysql_identifier($_GET['tablename']) . ";")->fetchAll();
        $table_data['columns'] = [];
        foreach ($columns as $column_index => $column) {
            preg_match('~^([^( ]+)(?:\\((.+)\\))?( unsigned)?( zerofill)?$~', $column["Type"], $column_type_matches);

            $column_data_type = $column_type_matches[1];
            $column_attribute = '';
            if (isset($data_type_attributes[$column_data_type]) && $data_type_attributes[$column_data_type] === 'numeric') {
                // zerofill & unsigned
                $column_attribute .= str_replace(' ', '', $column_type_matches[3]);
                if (!empty($column_type_matches[4])) {
                    $column_attribute .= ' ' . str_replace(' ', '', $column_type_matches[4]);
                }
            } elseif (isset($data_type_attributes[$column_data_type]) && $data_type_attributes[$column_data_type] === 'collation') {
                $column_attribute = $column["Collation"];
            }

            $table_column_data                      = [];
            $table_column_data['name']              = $column["Field"];
            $table_column_data['type']              = $column_data_type;
            $table_column_data['length']            = (isset($column_type_matches[2])) ? $column_type_matches[2] : '';
            $table_column_data['attribute']         = $column_attribute;
            $table_column_data['null']              = ($column["Null"] === "YES") ? true : false;
            $table_column_data['has_default_value'] = ($column["Default"] === null) ? false : true;
            $table_column_data['default_value']     = $column["Default"];
            $table_column_data['comment']           = $column["Comment"];
            // @todo: primary key?

            if ($column["Extra"] === "auto_increment") {
                $table_data['auto_increment_field'] = $column_index;
            }

            $table_data['columns'][] = $table_column_data;
        }
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

    echo json_encode($return_data);
    exit;
