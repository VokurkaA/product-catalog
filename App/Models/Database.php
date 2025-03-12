<?php
namespace App\Models;

use PDO;
use PDOException;

class Database
{
    private static $host;
    private static $port;
    private static $dbname;
    private static $user;
    private static $password;
    private static $initialized = false;

    private static function initialize()
    {
        if (!self::$initialized) {
            $env = parse_ini_file('.env');
            self::$host = $env['POSTGRES_HOST'];
            self::$port = $env['POSTGRES_PORT'];
            self::$dbname = $env['POSTGRES_NAME'];
            self::$user = $env['POSTGRES_USER'];
            self::$password = $env['POSTGRES_PASSWORD'];
            self::$initialized = true;
        }
    }

    private static function connect()
    {
        self::initialize();
        try {
            $dsn = "pgsql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$dbname . ";";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            $pdo = new PDO($dsn, self::$user, self::$password, $options);
            return $pdo;
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    public static function query($sql, $params = [])
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function prepare($sql)
    {
        $pdo = self::connect();
        return $pdo->prepare($sql);
    }
}
