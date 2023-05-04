<?php

class Database
{
    private static mysqli $connect;

    public static function connectDatabase(): mysqli
    {
        self::$connect = new mysqli(
            'sql107.epizy.com',
            'epiz_34117063',
            '040523rjh438201',
            'epiz_34117063_esp_databasev2'
        );

        return self::$connect ?: self::$connect->connect_error;

    }
}