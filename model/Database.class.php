<?php
class Database {
    private $servername = "localhost";
    private $dbName = "quiz";
    private $username = "root";
    private $password = "";

    protected function connect() {
        $conn = null;

        try {
            $conn = new PDO("mysql:host=$this->servername;dbname=$this->dbName", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            echo "Connection to database failed: " . $e->getMessage();
        }

        return $conn;
    }
}