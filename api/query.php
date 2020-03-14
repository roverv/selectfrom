<?php

    require_once '_header.php';

        $query = $_POST['query'];
//    $query = "SELECT * FROM user JOIN organisation ON user.organisation_id = organisation.id LIMIT 5;";

    try {
        $q = $pdo->query($query);
    } catch (Exception $e) {
        echo json_encode([
          'result'  => 'error',
          'message' => $e->getMessage(),
        ]);
        exit;
    }

    foreach (range(0, $q->columnCount() - 1) as $column_index) {
        $columns_meta[] = $q->getColumnMeta($column_index);
    }

    echo json_encode([
      'result'        => 'success',
      'affected_rows' => $q->rowCount(),
      'rows'          => $q->fetchAll(PDO::FETCH_NUM),
      'columns_meta'   => $columns_meta,
    ]);
    exit;

    print_r($result);
    print_r($q->rowCount());
    print_r($q->fetchAll()[0]);
    exit;

    var_dump($q);

    if ($result) {
        print_r($q->rowCount());
    } else {
        print_r($pdo->errorInfo());
    }

    exit;


    $result      = @($unbuffered ? mysql_unbuffered_query($query, $this->_link) : mysql_query($query,
      $this->_link)); // @ - mute mysql.trace_mode
    $this->error = "";
    if (!$result) {
        $this->errno = mysql_errno($this->_link);
        $this->error = mysql_error($this->_link);

        return false;
    }
    if ($result === true) {
        $this->affected_rows = mysql_affected_rows($this->_link);
        $this->info          = mysql_info($this->_link);

        return true;
    }


    //    $result = $connection->store_result();
    var_dump($rows);

    echo json_encode($rows);
    exit;

