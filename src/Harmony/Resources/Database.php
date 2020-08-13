<?php
namespace Harmony\Resources;

use PDO;
use PDOException;

class Database
{
    private static $connection = null;

    public static function getConnection()
    {
        $env = new Environment();
        extract($env->getByPrefix("DB"));
        if (is_null(self::$connection)) {
            try {
                switch ($DB_TYPE) {
                    case "pgsql":
                        self::$connection = new PDO(
                            "pgsql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};user={$DB_USER};password={$DB_PASS}"
                        );
                        break;

                    case "mysql":
                    case "mariadb":
                        self::$connection = new \PDO(
                            "mysql:dbname={$DB_NAME};host={$DB_HOST};port={$DB_PORT};charset=utf8",
                            $DB_USER, $DB_PASS
                        );

                        self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                        self::$connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
                        break;
                }
            } catch (PDOException $e) {
                header($_SERVER["SERVER_PROTOCOL"]." 500 Internal Error", true, 405);
                echo "Error 500 - Internal Error<br/>Harmony has crashed while connecting to Database.
                    <br/>Database Connection Type: PDO {$DB_TYPE}
                    <br/>Database: {$DB_NAME}
                    <br/>Please contact the administrator.";
                die();
            }
        }

        return self::$connection;
    }

    public static function closeConnection()
    {
        if (!is_null(self::$connection))
            self::$connection = null;
    }
}