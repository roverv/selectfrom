<?php

    require_once '_header.php';

    $rows = $pdo->query("SHOW DATABASES")->fetchAll();
    $databases = array_map(function($item) {
        return $item['Database'];
    }, $rows);
    echo json_encode($databases);
    exit;

