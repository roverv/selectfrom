<?php

    require_once '_header.php';

    $data = [];
    $row  = $pdo->query("SELECT COUNT(*) as amount_rows FROM  ".$_GET['tablename'])->fetch();

    $data['amount_rows'] = $row['amount_rows'] ?? 0;
    echo json_encode($data);
    exit;
