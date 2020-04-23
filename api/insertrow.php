<?php

    require_once '_header.php';
    require_once 'pdoDebug.php';

    //    $_POST = ['id' => '1964', 'lastname' => 'Bob en Susan2']; // test data

    //    INSERT INTO `site` (`id`, `organisation_id`, `type`, `online`, `abo_end`) VALUES
    //    (1, 1, 'standard', 1, '2013-04-19');

    $columns_with_values = $_POST;

    $query = "INSERT INTO " . escape_mysql_identifier($_GET['tablename']) . " ( ";

    $escaped_columns = array_map(function ($column) {
        return escape_mysql_identifier($column);
    }, array_keys($columns_with_values));

    $query .= implode(', ', $escaped_columns);

    $query .= ') VALUES (';

    $insert_values = [];
    $binded_values = [];
    foreach ($columns_with_values as $column => $cell_value) {
        if ($cell_value === null || strtolower($cell_value) === 'null') {
            $insert_values[] = 'NULL';
        } else {
            $insert_values[] = '?';
            $binded_values[] = $cell_value;
        }
    }
    $query .= implode(', ', $insert_values);
    $query .= ');';

    $prepare_values = array_values($binded_values);

    $pdo_statement = $pdo->prepare($query);

    $result_data          = [];
    $result_data['query'] = PdoDebugger::show($pdo_statement->queryString, $prepare_values);

    try {
        $pdo_statement = $pdo->prepare($query);
        $pdo_statement->execute($prepare_values);
        $result_data['result']          = 'success';
        $result_data['inserted_row_id'] = $pdo->lastInsertId();
    } catch (Exception $e) {
        $result_data['result']  = 'error';
        $result_data['message'] = $e->getMessage();
    }

    echo json_encode($result_data);
    exit;
