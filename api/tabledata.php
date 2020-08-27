<?php

    require_once '_header.php';

    $table_data = [];
    //  $rows = $pdo->query("SELECT * FROM " . $_GET['tablename']. " LIMIT 30")->fetchAll();
    // no need to get columns if we are retrieving more rows, they are already retrieved on the first call
    if(empty($_GET['offset'])) {
        $rows                  = $pdo->query("SHOW FULL COLUMNS FROM ".escape_mysql_identifier($_GET['tablename']))->fetchAll();
        $table_data['columns'] = $rows;
    }

    $amount_rows_per_page = 30; //todo: instelbaar maken

    $query                 = "SELECT * FROM ".escape_mysql_identifier($_GET['tablename'])." ";

    $filter_query = '';
    if (!empty($_GET['column']) && !empty($_GET['value']) && !empty($_GET['comparetype'])) {
        //todo: escape, duh
        if ($_GET['column'] == 'primarykey') {
            $primary_key_column = array_filter($table_data['columns'], function($column) {
                return ($column['Key'] === 'PRI');
            });
            if (count($primary_key_column) == 0) {
                echo json_encode([
                  'result'  => 'error',
                  'message' => 'Table has no PRIMARY KEY',
                ]);
            }
            $filter_query .= "WHERE `".$primary_key_column[0]['Field']."` = '".$_GET['value']."' ";

        } else if($_GET['comparetype'] == 'is') {
            $filter_query .= "WHERE `".$_GET['column']."` = '".$_GET['value']."' ";

        } else if($_GET['comparetype'] == 'like') {
            $filter_query .= "WHERE `".$_GET['column']."` LIKE '%".$_GET['value']."%' ";
        }
    }

    $query .= $filter_query;

    if (!empty($_GET['orderby']) && !empty($_GET['orderdirection'])) {
        $query .= "ORDER BY `".$_GET['orderby']."` ".$_GET['orderdirection']." ";
    }

    if(!empty($_GET['column']) && $_GET['column'] == 'primarykey'){}
    else if(!empty($_GET['limit']) && $_GET['limit'] == 'none'){}
    else {
        $query .= " LIMIT " . (int) $amount_rows_per_page . " ";
    }

    if(!empty($_GET['offset'])) {
        $offset = $amount_rows_per_page * (int) $_GET['offset'];
        $query .= " OFFSET " . $offset . " ";
    }

    $count_query = "SELECT COUNT(*) as amount_rows FROM " . escape_mysql_identifier($_GET['tablename']) . " "  . $filter_query;
    $count_result  = $pdo->query($count_query)->fetch();

    $table_data['amount_rows'] = $count_result['amount_rows'] ?? 0;

    $rows               = $pdo->query($query)->fetchAll();
    $table_data['data'] = $rows;
    echo json_encode($table_data);
    exit;
