<?php

    require_once '_header.php';

    $tables = $_POST['tables'];

    $affected_rows = 0;

    $foreign_key_check_query = "SET foreign_key_checks = 0;";
    $q = $pdo->query($foreign_key_check_query);
    $result_data['query'] = $foreign_key_check_query;

    $affected_tables = 0;
    foreach($tables as $table) {
        try {
            $truncate_table_query = "DROP TABLE  " . escape_mysql_identifier($table) . ";";
            $q = $pdo->query($truncate_table_query);
            $result_data['query'] .= $truncate_table_query;
            $affected_tables++;
        } catch (Exception $e) {
            echo json_encode([
              'result'  => 'error',
              'message' => $e->getMessage(),
            ]);
            exit;
        }
    }

    $result_data['result'] = 'success';
    $result_data['affected_tables'] = $affected_tables;

    echo json_encode($result_data);
    exit;
