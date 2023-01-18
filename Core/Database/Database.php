<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Core\Database;

use App\Core\Bootstrap;

class Database
{

    public static $db;
    private readonly string $dbHost;
    private readonly string $dbPort;
    private readonly string|null $dbName;
    private readonly string|null $dbUser;
    private readonly string|null $dbPassword;


    /**
     * @param \PDO $PDO
     */
    public static function connect(): void
    {
        if (!isset(self::$db)) {
            self::$db = @new \PDO(self::getDns(), self::getDbUser(), self::getDbPassword());
            self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$db->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8");
            self::$db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        }

    }


    protected static function getDns(): string
    {
        return "mysql:host=" . self::getDbHost() . ";port=" . self::getDbPort() . ";dbname=" . self::getDbName();
    }

    /**
     * @return string
     */
    protected static function getDbHost(): string
    {
        return Bootstrap::$config['MySQL']['host'] ?? 'localhost';
    }

    /**
     * @return string
     */
    protected static function getDbPort(): string
    {
        return Bootstrap::$config['MySQL']['port'] ?? '3306';
    }

    /**
     * @return string
     */
    protected static function getDbName(): string
    {
        return Bootstrap::$config['MySQL']['name'] ?? '';
    }

    /**
     * @return string
     */
    protected static function getDbUser(): string
    {
        return Bootstrap::$config['MySQL']['user'] ?? '';
    }

    /**
     * @return string
     */
    protected static function getDbPassword(): string
    {
        return Bootstrap::$config['MySQL']['password'] ?? '';
    }





}