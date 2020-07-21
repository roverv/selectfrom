<?php

    require_once '_header.php';
    require_once 'pdoDebug.php';

    //    var_dump($_POST);
    //    exit;

    $columns_with_values = $_POST;

    // @todo: moet ik de onderstaande values binden/escapen??

    $query = "CREATE TABLE " . escape_mysql_identifier($_POST['table_name']) . " ( ";

    $column_counter = 1;
    foreach ($_POST['columns'] as $column_index => $column) {
        $query .= escape_mysql_identifier($column['name']) . ' ';
        $query .= $column['type'];
        $query .= (!empty($column['length'])) ? "(" . $column['length'] . ") " : " ";

        if (!empty($column['attribute'])) {
            if (in_array($column['attribute'], ['unsigned', 'zerofill', 'unsigned zerofill'])) {
                $query .= strtoupper($column['attribute']) . " ";
            } else {
                $query .= "COLLATE " . $pdo->quote(strtoupper($column['attribute'])) . " ";
            }
        }

        if (!empty($column['null']) && $column['null'] === 'true') {
            $query .= "NULL ";
        } else {
            $query .= "NOT NULL ";
        }

        if (!empty($column['has_default_value']) && $column['has_default_value'] === 'true') {
            $query .= "DEFAULT  " . $pdo->quote($column['default_value']) . " ";
        }

        if (!empty($column['comment'])) {
            $query .= "COMMENT  " . $pdo->quote($column['comment']) . " ";
        }

        if (!empty($_POST['auto_increment_field']) && $_POST['auto_increment_field'] === $column['name']) {
            $query .= "AUTO_INCREMENT PRIMARY KEY ";
        }

        if($column_counter < count($_POST['columns'])) {
            $query .= ', ';
        }

        $column_counter++;
    }

    $query .= ") ";

    if (!empty($_POST['engine'])) {
        $query .= "ENGINE=" . $pdo->quote(strtoupper($_POST['engine'])) . " ";
    }

    if (!empty($_POST['collation'])) {
        $query .= "COLLATE " . $pdo->quote($_POST['collation']) . " ";
    }

    if (!empty($_POST['comment'])) {
        $query .= "COMMENT=" . $pdo->quote($_POST['comment']) . " ";
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
