<?php

    require_once '_header.php';


    $query = $_POST['query'];
    //        $query = "SELECT * FROM user JOIN organisation ON user.organisation_id = organisation.id LIMIT 5;";
    //        $query = "DELETE FROM country;
    //INSERT INTO country(code, name, name_nl) VALUES ('NL', 'netherlands', 'Nederland');
    //INSERT INTO country(code, name, name_nl) VALUES ('DE', 'Germany', 'Duitsland');";

    $query   = removeComments($query);
    $queries = split_sql($query);


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
      'columns_meta'  => $columns_meta,
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

    /**
     * Taken from: https://stackoverflow.com/questions/4747808/split-mysql-queries-in-array-each-queries-separated-by
     *
     * @param $sql_text
     * @return array|mixed
     */
    function split_sql($sql_text)
    {
        // Return array of ; terminated SQL statements in $sql_text.
        $re_split_sql = '%(?#!php/x re_split_sql Rev:20170816_0600)
        # Match an SQL record ending with ";"
        \s*                                     # Discard leading whitespace.
        (                                       # $1: Trimmed non-empty SQL record.
          (?:                                   # Group for content alternatives.
            \'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'  # Either a single quoted string,
          | "[^"\\\\]*(?:\\\\.[^"\\\\]*)*"      # or a double quoted string,
          | /\*[^*]*\*+(?:[^*/][^*]*\*+)*/      # or a multi-line comment,
          | \#.*                                # or a # single line comment,
          | --.*                                # or a -- single line comment,
          | [^"\';#]                            # or one non-["\';#-]
          )+                                    # One or more content alternatives
          (?:;|$)                               # Record end is a ; or string end.
        )                                       # End $1: Trimmed SQL record.
        %x';  // End $re_split_sql.
        if (preg_match_all($re_split_sql, $sql_text, $matches)) {
            return $matches[1];
        }

        return array();
    }

    /**
     * Taken from: https://stackoverflow.com/questions/9690448/regular-expression-to-remove-comments-from-sql-statement
     *
     * @param $sql_text
     * @return string
     */
    function removeComments($sql_text)
    {
        $regex = '/["\'`][^"\'`]*(?!\\\\)["\'`](*SKIP)(*F)|(?m-s:\s*(?:\-{2}|\#)[^\n]*$)|(?:\/\*.*?\*\/(?(?=(?m-s:[\t ]+$))[\t ]+))/s';
        // alternative, not using PCRE:
        // $RXSQLComments = '@(--[^\r\n]*)|(\#[^\r\n]*)|(/\*[\w\W]*?(?=\*/)\*/)@ms';
        $result = preg_replace($regex, '', $sql_text);

        return $result;
    }

