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

    public function __construct()
    {
        self::setDbHost(Bootstrap::$config['MySQL']['host'] ?? 'localhost');
        self::setDbPort(Bootstrap::$config['MySQL']['port'] ?? '3306');
        self::setDbUser(Bootstrap::$config['MySQL']['user'] ?? '');
        self::setDbPassword(Bootstrap::$config['MySQL']['password'] ?? '');
        self::setDbName(Bootstrap::$config['MySQL']['name'] ?? '');
    }

    /**
     * @param \PDO $PDO
     */
    public function connect(): void
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
    public function getDbHost(): string
    {
        return $this->dbHost;
    }

    /**
     * @return string
     */
    public function getDbPort(): string
    {
        return $this->dbPort;
    }

    /**
     * @return string|null
     */
    public function getDbName(): ?string
    {
        return $this->dbName;
    }

    /**
     * @return string|null
     */
    public function getDbPassword(): ?string
    {
        return $this->dbPassword;
    }

    /**
     * @return string|null
     */
    public function getDbUser(): ?string
    {
        return $this->dbUser;
    }

    /**
     * @param string $dbHost
     */
    public function setDbHost(string $dbHost): void
    {
        $this->dbHost = $dbHost;
    }

    /**
     * @param string|null $dbName
     */
    public function setDbName(?string $dbName): void
    {
        $this->dbName = $dbName;
    }

    /**
     * @param string|null $dbPassword
     */
    public function setDbPassword(?string $dbPassword): void
    {
        $this->dbPassword = $dbPassword;
    }

    /**
     * @param string $dbPort
     */
    public function setDbPort(string $dbPort): void
    {
        $this->dbPort = $dbPort;
    }

    /**
     * @param string|null $dbUser
     */
    public function setDbUser(?string $dbUser): void
    {
        $this->dbUser = $dbUser;
    }
}