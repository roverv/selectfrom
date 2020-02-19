<?php

    require_once '_header.php';

    $table_data = [];
    //  $rows = $pdo->query("SELECT * FROM " . $_GET['tablename']. " LIMIT 30")->fetchAll();
    $rows                  = $pdo->query("SHOW FULL COLUMNS FROM ".$_GET['tablename'])->fetchAll();
    $table_data['columns'] = $rows;
    $query                 = "SELECT * FROM ".$_GET['tablename']." ";

    if(!empty($_GET['type']) && $_GET['type'] == 'groupby') {
        $query .= "GROUP BY `".$_GET['column']."` ";
    }
    else if (!empty($_GET['column']) && !empty($_GET['value'])) {
        //todo: escape, duh
        if ($_GET['column'] == 'id') {
            $query .= "WHERE `".$_GET['column']."` = '".$_GET['value']."' ";
        } else {
            $query .= "WHERE `".$_GET['column']."` LIKE '%".$_GET['value']."%' ";
        }
    }

    if (!empty($_GET['orderby']) && !empty($_GET['orderdirection'])) {
        $query .= "ORDER BY `".$_GET['orderby']."` ".$_GET['orderdirection']." ";
    }

    if(empty($_GET['column']) || $_GET['column'] != 'id') {
        $query .= " LIMIT 90 ";
    }

    $rows               = $pdo->query($query)->fetchAll();
    $table_data['data'] = $rows;
    echo json_encode($table_data);
    exit;
