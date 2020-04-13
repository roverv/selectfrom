<?php
    session_start();

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');

    $host    = 'localhost';
    $db      = $_GET['db'] ?? '';
    $user    = 'root';
    $pass    = '';
    $charset = 'utf8mb4';

    $dsn     = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => true,
      // this must be true if we want to run running multiple queries from one sql text
    ];
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }


    /**
     * Escape query identifiers like tablename, table column or database name
     * use as: UPDATE user SET escapeWithBacktick('name') = :name
     * See: https://phpdelusions.net/pdo/sql_injection_example and https://phpdelusions.net/pdo_examples/insert_helper
     * NOTE: MYSQL ONLY!
     *
     * @param $name
     * @return string
     */
    function escape_mysql_identifier($field)
    {
        return "`" . str_replace("`", "``", $field) . "`";
    }

