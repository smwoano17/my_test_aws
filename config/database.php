
<?php

class Database
{
    private $host = "employees-management.cxgg64820tc9.ap-southeast-2.rds.amazonaws.com";
    private $dbname = "employee_management";
    private $username = "admin";
    private $password = "lmamvypkw1av";

    public $conn;

    public function connect()
    {
        try {

            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            return $this->conn;

        } catch (PDOException $e) {

            die("Database Connection Failed: " . $e->getMessage());

        }
    }
}
