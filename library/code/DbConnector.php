<?php
require_once 'constants.php';

class DbConnector
{
    private $dbConnection = null;

    public function getConnection() {
        if (is_null($this->dbConnection)) {
            $dbParams = parse_ini_file(SECRET_DB_PARAMS_FILE_PATH);
            $dbName = $dbParams['db_name'];
            $dbUserName = $dbParams['db_user'];
            $dbPassword = $dbParams['db_pass'];
            $dsn = "mysql:host=localhost;dbname=$dbName";
            $this->dbConnection = new PDO($dsn, $dbUserName, $dbPassword);
        }
        return $this->dbConnection;
    }
}