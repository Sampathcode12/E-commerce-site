<?php

class Database
{
    private $host = "localhost";   // Database host
    private $dbname = "ecommerce"; // Database name
    private $username = "root";    // Database username
    private $password = "";        // Database password
    private $conn;                 // Connection instance

    // Get database connection
    public function getConnection()
    {
        $this->conn = null;

        try {
            // Create a new PDO instance
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
        } catch (PDOException $exception) {
            die("Database connection error: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
?>
