<?php

    require_once '_header.php';

    $rows = $pdo->query("SELECT * FROM information_schema.columns WHERE table_schema = '" . $_GET['db'] . "' ORDER BY table_name,ordinal_position")->fetchAll();

    $tables_with_columns = [];
    foreach ($rows as $_row) {
        $tables_with_columns[$_row['TABLE_NAME']][] = $_row['COLUMN_NAME'];
    }
    echo json_encode($tables_with_columns);
    exit;




