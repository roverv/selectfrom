<?php

    require_once '_header.php';

    //  $rows = $pdo->query("SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME")->fetchAll();
    $rows = $pdo->query("SHOW TABLE STATUS")->fetchAll();
    echo json_encode($rows);
    exit;




