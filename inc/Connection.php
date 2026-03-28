<?php
class Connection
{
    private $servername = "localhost";
    private $username = "username";
    private $password = "password";
    private $dbname = "mydb";

    public function getConnection()
    {
        try {
            $conn = new PDO("mysql:host=$this->servername;dbname=,$this->dbname", $this->username, $this->password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
            echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
