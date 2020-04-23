<?php

    require_once '_header.php';
    require_once 'pdoDebug.php';

//    $_POST = ['id' => '1964', 'lastname' => 'Bob en Susan2']; // test data

    $columns_with_values = $_POST;

    $query = "UPDATE " . escape_mysql_identifier($_GET['tablename']) . " SET ";

    $update_values = [];
    $binded_values = [];
    foreach ($columns_with_values as $column => $cell_value) {
        if($cell_value === null || strtolower($cell_value) === 'null') {
            $update_values[] = escape_mysql_identifier($column) . ' = NULL ';
        }
        else {
            $update_values[] = escape_mysql_identifier($column) . ' = ? ';
            $binded_values[] = $cell_value;
        }
    }
    $query .= implode(', ', $update_values);

    $query .= "WHERE " . escape_mysql_identifier($_GET['column']) . " = ? ";
    $query .= "LIMIT 1 ";

    $prepare_values   = array_values($binded_values);
    $prepare_values[] = $_GET['value'];

    $pdo_statement = $pdo->prepare($query);

    $result_data = [];
    $result_data['query'] = PdoDebugger::show($pdo_statement->queryString, $prepare_values);
    try {
        $pdo_statement = $pdo->prepare($query);
        $pdo_statement->execute($prepare_values);
        $result_data['result']  = 'success';
        $result_data['affected_rows'] = $pdo_statement->rowCount();
    } catch (Exception $e) {
        $result_data['result']  = 'error';
        $result_data['message'] = $e->getMessage();
    }

    echo json_encode($result_data);
    exit;
