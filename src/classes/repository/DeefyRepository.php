<?php

namespace iutnc\deefy\repository;

class DeefyRepository
{
    private static ?array $config = null;
    private static ?PDO $db = null;

    public static function setConfig(string $config_file): void
    {
        self::$config = parse_ini_file($config_file);
    }

    public static function getInstance(): PDO
    {
        if (self::$db == null) {
            $driver = self::$config['driver']; //mysql
            $host = self::$config['host']; //host=localhost ???
            $dbname = self::$config['dbname']; //deefy

            $dsn = "$driver:$host;dbname=$dbname";

            self::$db = new PDO($dsn, self::$config['user'], self::$config['password']);
        }

        return self::$db;
    }
}