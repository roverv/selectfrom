<?php

    require_once '_header.php';

    $table_data = [];
    //  $rows = $pdo->query("SELECT * FROM " . $_GET['tablename']. " LIMIT 30")->fetchAll();
    // no need to get columns if we are retrieving more rows, they are already retrieved on the first call
    if(empty($_GET['offset'])) {
        $rows                  = $pdo->query("SHOW FULL COLUMNS FROM ".$_GET['tablename'])->fetchAll();
        $table_data['columns'] = $rows;
    }

    $amount_rows_per_page = 90; //todo: instelbaar maken

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

    if(!empty($_GET['column']) && $_GET['column'] = 'id'){}
    else if(!empty($_GET['limit']) && $_GET['limit'] = 'none'){}
    else {
        $query .= " LIMIT " . (int) $amount_rows_per_page . " ";
    }

    if(!empty($_GET['offset'])) {
        $offset = $amount_rows_per_page * (int) $_GET['offset'];
        $query .= " OFFSET " . $offset . " ";
    }

    $rows               = $pdo->query($query)->fetchAll();
    $table_data['data'] = $rows;
    echo json_encode($table_data);
    exit;
