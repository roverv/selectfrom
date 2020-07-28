<?php

    require_once '_header.php';
    require_once 'db_functions.php';
    require_once 'classes/TableStatus.php';

    // @todo: moet ik de onderstaande values binden/escapen??
    // @todo: AFTER kolom toevoegen
    // @todo: AFTER toepassen bij CHANGE/ADD
    // @todo: AUTO INCREMENT waarde kolom toevoegen
    // @todo: GOED TESTEN

    // get current table data
    $data_type_attributes = getDataTypeAttributes();
    $table_status_data = $pdo->query("SHOW TABLE STATUS WHERE name = '" . $_GET['tablename'] . "';")->fetch();
    $columns_data               = $pdo->query("SHOW FULL COLUMNS FROM " . escape_mysql_identifier($_GET['tablename']) . ";")->fetchAll();
    $original_table_status = TableStatus::createFromDatabase($table_status_data, $columns_data, $data_type_attributes);

    $new_table_status = TableStatus::createFromPost($_POST, $data_type_attributes);

    $query = "ALTER TABLE " . escape_mysql_identifier($original_table_status->getName()) . " ";

    $changed_columns = array_filter($new_table_status->getColumns(), function(ColumnStructure $column) use($pdo, $original_table_status) {

        // new column, skip
        if(empty($column->getOriginalFieldName())) {
            return false;
        }
        // name of column has changed
        if($column->getName() !== $column->getOriginalFieldName()) {
            return true;
        }

        $original_column = $original_table_status->getColumnByName($column->getOriginalFieldName());

        if($original_column) {

            $old_structure = $original_column->asQueryWithoutName($pdo);
            $new_structure = $column->asQueryWithoutName($pdo);

            // structure has changed
            if($old_structure != $new_structure) {
                return true;
            }
        }

        return false;
    });


    $new_columns = array_filter($new_table_status->getColumns(), function(ColumnStructure $column) {
        // name of column has changed
        if(empty($column->getOriginalFieldName())) {
            return true;
        }

        return false;
    });

    $current_columns = array_map(function(ColumnStructure $column) {
        return $column->getOriginalFieldName();
    }, $new_table_status->getColumns());

    $deleted_columns = array_filter($original_table_status->getColumns(), function(ColumnStructure $original_column) use($current_columns) {
        if(!in_array($original_column->getName(), $current_columns)) {
            return true;
        }

        return false;
    });

    array_walk($new_columns, function(ColumnStructure $new_column) use(&$query, $pdo) {
        $query .= 'ADD ' . escape_mysql_identifier($new_column->getName()) . ' ' . $new_column->asQueryWithoutName($pdo) . ", ";
    });

    array_walk($deleted_columns, function(ColumnStructure $deleted_column) use(&$query, $pdo) {
        $query .= 'DROP ' . escape_mysql_identifier($deleted_column->getName()) . ', ';
    });

    array_walk($changed_columns, function(ColumnStructure $changed_column) use(&$query, $pdo) {
        $query .= 'CHANGE ' . escape_mysql_identifier($changed_column->getOriginalFieldName()) . ' ';
        $query .=  escape_mysql_identifier($changed_column->getName()) . ' ';
        $query .=  $changed_column->asQueryWithoutName($pdo) . ', ';
    });

    if($original_table_status->getName() != $new_table_status->getName()) {
        $query .=  'RENAME TO ' . escape_mysql_identifier($new_table_status->getName()) . ', ';
    }

    if ($original_table_status->getEngine() != $new_table_status->getEngine()) {
        $query .= "ENGINE=" . $pdo->quote(strtoupper($new_table_status->getEngine())) . " ";
    }

    if ($original_table_status->getCollation() != $new_table_status->getCollation()) {
        $query .= "COLLATE " . $pdo->quote($new_table_status->getCollation()) . " ";
    }

    if ($original_table_status->getComment() != $new_table_status->getComment()) {
        $query .= "COMMENT=" . $pdo->quote($new_table_status->getComment()) . " ";
    }

    if(substr($query, -2) == ', ') {
        $query = substr($query, 0, -2);
    }

    $query.= ";";

    $result_data          = [];
    $result_data['query'] = $query;

    try {
        $pdo->query($query);
        $result_data['result'] = 'success';
    } catch (Exception $e) {
        $result_data['result']  = 'error';
        $result_data['message'] = $e->getMessage();
    }

    echo json_encode($result_data);
    exit;
