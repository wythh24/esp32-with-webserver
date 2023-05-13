<?php

class Database
{
    private static mysqli $connect;

    public static function connectDatabase(): mysqli
    {
        self::$connect = new mysqli(
            'localhost',
            'root',
            '20001',
            'esp_database'
        );

        return self::$connect ?: self::$connect->connect_error;

    }
}