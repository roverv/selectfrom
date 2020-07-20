<?php

    require_once '_header.php';

    $collations = $pdo->query("SHOW COLLATION")->fetchAll();

    // sort by the ones which are default, first
    uasort($collations, function ($a, $b) {
        return $a['Collation'] <=> $b['Collation'];
    });

    // group by charset, only get the names
    $collations = array_reduce($collations, function($collations_grouped, $collation) {
        $collations_grouped[$collation['Charset']][] = $collation['Collation'];
        return $collations_grouped;
    });

    // sort alphabetically by key
    ksort($collations);

    $engines = $pdo->query("SHOW ENGINES")->fetchAll();
    // filter out not supported engines
    $engines = array_filter($engines, function($engine) {
        return ($engine['Support'] == 'DEFAULT' || $engine['Support'] == 'YES');
    });
    // we only need the names
    $engines = array_map(function($engine) {
        return $engine['Engine'];
    }, $engines);

    $data_types = [
      'Numeric' => ['tinyint', 'smallint', 'mediumint', 'int', 'bigint', 'float', 'double', 'decimal', 'bit'],
      'Date and time' => ['date', 'time', 'datetime', 'timestamp', 'year'],
      'String' => [
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
      'JSON' => ['JSON'],
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

    echo json_encode([
      'collations'           => $collations,
      'engines'              => $engines,
      'data_types'           => $data_types,
      'data_type_attributes' => $data_type_attributes,
    ]);
    exit;
