<?php

use mysqli;

class Database
{
    private string $host = "localhost";
    private string $userName = "root";
    private string $password = "20001";
    private string $database = "esp_database";

    public mysqli $connect;

    public function __construct()
    {
        $this->connect = new mysqli($this->host, $this->userName, $this->password, $this->database);

        if ($this->connect->connect_error) {
            die("Connection failed: " . $this->connect->connect_error);
        }
    }

    public function test_connection(): bool
    {
        return (bool)$this->connect;
    }

    public function __destruct()
    {
        $this->connect->close();
    }
}
