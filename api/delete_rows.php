<?php

    require_once '_header.php';

    $delete_by_column = $_POST['delete_by_column'];
    $delete_by_rows = $_POST['delete_by_rows'];

    $affected_rows = 0;
    foreach($delete_by_rows as $row_value) {
        try {
            $q = $pdo->query("DELETE FROM `" . $_GET['tablename'] . "` WHERE `" . $delete_by_column . "` = '" . $row_value . "' LIMIT 1");
            $affected_rows += $q->rowCount();
        } catch (Exception $e) {
            echo json_encode([
              'result'  => 'error',
              'message' => $e->getMessage(),
            ]);
            exit;
        }
    }

    echo json_encode([
      'result'        => 'success',
      'affected_rows' => $affected_rows,
    ]);
    exit;
