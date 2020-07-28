<?php

    require_once '_header.php';
    require_once 'pdoDebug.php';
    require_once 'db_functions.php';
    require_once 'classes/TableStatus.php';

    $columns_with_values = $_POST;

    // @todo: moet ik de onderstaande values binden/escapen??

    $data_type_attributes = getDataTypeAttributes();
    $table_status = TableStatus::createFromPost($_POST, $data_type_attributes);

    $query = "CREATE TABLE " . escape_mysql_identifier($table_status->getName()) . " ( ";

    $column_counter = 1;
    foreach ($table_status->getColumns() as $column_index => $column) {
        $query .= escape_mysql_identifier($column->getName()) . ' ';

        $query .= $column->asQueryWithoutName($pdo);

        if($column_counter < count($table_status->getColumns())) {
            $query .= ', ';
        }

        $column_counter++;
    }

    $auto_increment_column = $table_status->getAutoIncrementColumn();
    if($auto_increment_column) {
        $query .= ", PRIMARY KEY (" . escape_mysql_identifier($auto_increment_column->getName()) . ") ";
    }

    $query .= ") ";

    if (!empty($table_status->getEngine())) {
        $query .= "ENGINE=" . $pdo->quote(strtoupper($table_status->getEngine())) . " ";
    }

    if (!empty($table_status->getCollation())) {
        $query .= "COLLATE " . $pdo->quote($table_status->getCollation()) . " ";
    }

    if (!empty($table_status->getComment())) {
        $query .= "COMMENT=" . $pdo->quote($table_status->getComment()) . " ";
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



    //    'table_name' => string 'abcuser' (length=7)
    //    'engine' => string 'InnoDB' (length=6)
    //    'collation' => string 'utf8_bin' (length=8)
    //    'comment' => string '123' (length=3)
    //    auto_increment_field
    //    'columns' =>
    //    array (size=3)
    //      0 =>
    //        array (size=9)
    //          'name' => string 'id' (length=2)
    //          'type' => string 'int' (length=3)
    //          'length' => string '8' (length=1)
    //          'attribute' => string 'unsigned' (length=8)
    //          'null' => string 'false' (length=5)
    //          'auto_increment' => string 'true' (length=4)
    //          'has_default_value' => string 'false' (length=5)
    //          'default_value' => string '' (length=0)
    //          'comment' => string '' (length=0)
    //      1 =>
    //        array (size=9)
    //          'name' => string 'abc' (length=3)
    //          'type' => string 'char' (length=4)
    //          'length' => string '8' (length=1)
    //          'attribute' => string 'armscii8_bin' (length=12)
    //          'null' => string 'true' (length=4)
    //          'auto_increment' => string 'true' (length=4)
    //          'has_default_value' => string 'true' (length=4)
    //          'default_value' => string 'jkwad' (length=5)
    //          'comment' => string 'wajd' (length=4)
    //      2 =>
    //        array (size=9)
    //          'name' => string 'awd21' (length=5)
    //          'type' => string 'double' (length=6)
    //          'length' => string '12' (length=2)
    //          'attribute' => string 'zerofill' (length=8)
    //          'null' => string 'true' (length=4)
    //          'auto_increment' => string 'false' (length=5)
    //          'has_default_value' => string 'false' (length=5)
    //          'default_value' => string '' (length=0)
    //          'comment' => string '' (length=0)


    //    CREATE TABLE `user_file` (
    //    `id` mediumint(8) UNSIGNED NOT NULL,
    //    `user_id` mediumint(8) UNSIGNED DEFAULT NULL,
    //    `variant` varchar(40) DEFAULT NULL,
    //    `ext_type` smallint(6) UNSIGNED DEFAULT NULL,
    //    `type` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
    //    `code` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
    //    `originalfilename` varchar(255) DEFAULT NULL,
    //    `filelocation` varchar(255) DEFAULT NULL,
    //    `name` varchar(255) DEFAULT NULL,
    //    `description` text,
    //    `insertTS` datetime DEFAULT NULL,
    //    `insertUser` mediumint(8) UNSIGNED DEFAULT NULL,
    //    `updateTS` datetime DEFAULT NULL,
    //    `updateUser` mediumint(8) UNSIGNED DEFAULT NULL
    //    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
