<?php

    require_once '_header.php';

    $query = "SELECT * FROM " . escape_mysql_identifier($_GET['tablename']) . " ";
    $query .= "WHERE " . escape_mysql_identifier($_GET['column']) . " = ? ";
    $query .= " LIMIT 1 ";

    $pdo_statement = $pdo->prepare($query);
    $pdo_statement->execute([$_GET['value']]);

    $row                = $pdo_statement->fetch();
    $table_data['data'] = $row;
    echo json_encode($table_data);
    exit;
