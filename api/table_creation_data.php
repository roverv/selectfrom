<?php

    require_once '_header.php';
    require_once 'db_functions.php';
    require_once 'classes/TableStatus.php';

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

    $data_type_attributes = getDataTypeAttributes();

    if (!empty($_GET['tablename'])) {
        $table_status_data     = $pdo->query("SHOW TABLE STATUS WHERE name = '" . $_GET['tablename'] . "';")->fetch();
        $columns_data          = $pdo->query("SHOW FULL COLUMNS FROM " . escape_mysql_identifier($_GET['tablename']) . ";")->fetchAll();
        $table_data = TableStatus::createFromDatabase($table_status_data, $columns_data,
          $data_type_attributes);
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
