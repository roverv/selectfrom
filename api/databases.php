<?php

    require_once '_header.php';

    $rows = $pdo->query("SELECT SCHEMA_NAME AS database_name, DEFAULT_COLLATION_NAME AS database_collation FROM INFORMATION_SCHEMA.SCHEMATA")->fetchAll();
    $databases = array_map(function($row) {
        return ['name' => $row['database_name'], 'collation' => $row['database_collation']];
    }, $rows);
    echo json_encode($databases);
    exit;

