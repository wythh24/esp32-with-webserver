<?php

class Database
{
    private static mysqli $connect;

    public static function connectDatabase(): mysqli
    {
        self::$connect = new mysqli(
            'host_name',
            'user_name',
            'password',
            'database_name'
        );

        return self::$connect ?: self::$connect->connect_error;

    }
}
