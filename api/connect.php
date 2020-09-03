<?php

    session_start();

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');

    $host     = empty($_POST['host']) ? 'localhost' : $_POST['host'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    // @todo: not finished

    $host    = 'localhost';
    $db      = $_GET['db'] ?? '';
    $user    = 'root';
    $charset = 'utf8mb4';

//    $dsn     = "mysql:host=$host;dbname=$db;charset=$charset";
    $dsn     = "mysql:host=$host;charset=$charset";
    $options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => true,
      // this must be true if we want to run running multiple queries from one sql text
    ];

    try {
        $pdo = new PDO($dsn, $username, $password, $options);
    } catch (\PDOException $e) {
        echo json_encode([
          'result'  => 'error',
          'message' => $e->getMessage(),
        ]);
        exit;
//        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }

    $_SESSION['host']     = $host;
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;

    echo json_encode([
      'result'  => 'success',
    ]);
    exit;
