<?php
function db_connect() {
    // Is the secrets file there?
    $secrets_file_path = '../db-params.php';
    if (!file_exists($secrets_file_path)) {
        print 'DB connection file not found';
        exit(1);
    }
    // Grab the secrets.
    require_once $secrets_file_path;
    if (!isset($db_name) || !isset($db_user) || !isset($db_pw)) {
        print 'DB connection info missing';
        exit(1);
    }
    // Make a DSN
    $dsn = "mysql:host=localhost;dbname=$db_name";
    // Connect.
    $pdo_connection = new PDO($dsn, $db_user, $db_pw);
    return $pdo_connection;
}
