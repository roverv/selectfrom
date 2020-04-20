<?php

    require_once '_header.php';

    $sql_text = $_POST['query'];

    $sql_text = removeComments($sql_text);
    $queries  = split_sql($sql_text);

    $query_results = [];
    foreach ($queries as $query) {
        if(empty(trim($query))) continue;

        $result_data = ['query' => $query];
        try {
            $query_result = $pdo->query($query);
        } catch (Exception $e) {
            $result_data['result']  = 'error';
            $result_data['message'] = $e->getMessage();
            $query_results[]        = $result_data;
            continue;
        }

        $result_data['result'] = 'success';

        // if the result has columns, it means it has data (eg SELECT or EXPLAIN query)
        if ($query_result->columnCount()) {
            $columns_meta = [];
            foreach (range(0, $query_result->columnCount() - 1) as $column_index) {
                $columns_meta[] = $query_result->getColumnMeta($column_index);
            }
            $result_data['columns_meta'] = $columns_meta;
            $result_data['rows']         = $query_result->fetchAll(PDO::FETCH_NUM); // fetch as num, because of possible joins with the same column names
            $result_data['row_count']    = $query_result->rowCount();
            $result_data['type']         = 'data';
        } else {
            // DML (data manipulation language) queries
            $result_data['affected_rows'] = $query_result->rowCount();
            $result_data['type']          = 'change';
        }
        $query_results[] = $result_data;
    }

    echo json_encode($query_results);
    exit;





    // ---------------------------------------- TESTING DATA

    //        $query = "SELECT * FROM user JOIN organisation ON user.organisation_id = organisation.id LIMIT 5;";
    //        $query = "DELETE FROM country;
    //INSERT INTO country(code, name, name_nl) VALUES ('NL', 'netherlands', 'Nederland');
    //INSERT INTO country(code, name, name_nl) VALUES ('DE', 'Germany', 'Duitsland');";
    $query = "INSERT INTO country(code, name, name_nl) VALUES ('NL', 'netherlands', 'Nederland');";
    $query = "DELETE FROM country;";

    $stmt = $pdo->query('DROP TABLE IF EXISTS test;
  CREATE TABLE test(id INT); 
  -- allala kfjds
  INSERT INTO test(id) VALUES (1); 
  SELECT * FROM test');
    do {
//        var_dump($pdo->info);
        if($stmt->columnCount()) {
            var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));
        }

    } while ($stmt->nextRowset());


//    $query   = removeComments($query);
//    $queries = split_sql($query);
    $stmt = $pdo->prepare("DELETE FROM country;");
    $result = $stmt->execute();
    echo getQueryType("DELETE FROM country;");
    echo $stmt->rowCount();
    var_dump($result);
    $result->columnCount();
    $stmt = $pdo->prepare("SELECT * FROM country;");
    $result = $stmt->execute();
    var_dump($result);
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


    /**
     * Return the type of the query.
     * Taken from: https://github.com/jasny/dbquery-mysql/blob/master/src/Jasny/DB/MySQL/QuerySplitter.php
     *
     * @param string $sql  SQL query statement (or an array with parts)
     * @return string
     */
     function getQueryType($sql)
    {
        if (is_array($sql)) $sql = key($sql);

        $matches = null;
        if (!preg_match('/^\s*(SELECT|INSERT|REPLACE|UPDATE|DELETE|TRUNCATE|CALL|DO|HANDLER|LOAD\s+(?:DATA|XML)\s+INFILE|(?:ALTER|CREATE|DROP|RENAME)\s+(?:DATABASE|TABLE|VIEW|FUNCTION|PROCEDURE|TRIGGER|INDEX)|PREPARE|EXECUTE|DEALLOCATE\s+PREPARE|DESCRIBE|EXPLAIN|HELP|USE|LOCK\s+TABLES|UNLOCK\s+TABLES|SET|SHOW|START\s+TRANSACTION|BEGIN|COMMIT|ROLLBACK|SAVEPOINT|RELEASE SAVEPOINT|CACHE\s+INDEX|FLUSH|KILL|LOAD|RESET|PURGE\s+BINARY\s+LOGS|START\s+SLAVE|STOP\s+SLAVE)\b/si', $sql, $matches)) return null;

        $type = strtoupper(preg_replace('/\s++/', ' ', $matches[1]));
        if ($type === 'BEGIN') $type = 'START TRANSACTION';

        return $type;
    }
