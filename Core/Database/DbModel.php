<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Core\Database;


use App\Core\Model;

abstract class DbModel extends Model
{

    /**
     * Vrátí všechny řádky
     *
     * @param string $sql SQL dotaz
     * @param array $parameters
     * @return array|false
     */
    public static function selectAll(string $sql, array $parameters = []): array|false
    {
        $query = Database::$db->prepare($sql);
        $query->execute($parameters);
        return $query->fetchAll();
    }

    /**
     * Vrátí první řádek
     *
     * @param string $sql
     * @param array $parameters
     * @return array|false
     */
    public static function selectOne(string $sql, array $parameters = []): array|false
    {
        $guery = Database::$db->prepare($sql);
        $guery->execute($parameters);
        return $guery->fetch();
    }

    /**
     * Spustí dotaz a vrátí počet ovlivněných řádků
     * @param string $sql
     * @param array $parameters
     * @return int
     */
    public static function query(string $sql, array $parameters = array()) : int
    {
        $navrat = Database::$db->prepare($sql);
        $navrat->execute($parameters);
        return $navrat->rowCount();
    }


    /**
     * Vložení dat
     *
     * @param string $table Název tabulky
     * @param array $parameters
     * @return bool|int
     */
    public static function insert(string $table, array $parameters = []): bool|int
    {
        return self::query("INSERT INTO `$table` (`".
            implode('`, `', array_keys($parameters)).
            "`) VALUES (".str_repeat('?,', sizeOf($parameters)-1)."?)",
            array_values($parameters));
    }

    /**
     * Aktualtizace dat
     *
     * @param string $table Název tabulky
     * @param array $values Hodnoty ke změně
     * @param string $conditional Podmínka pro aktualizaci
     * @param array $parameter
     * @return bool|int
     */
    public static function update(string $table, array $values, string $conditional, array $parameter): bool|int
    {
        return self::query("UPDATE `$table` SET ".
            implode('` = ?, `', array_keys($values)).
            " = ? " . $conditional,
            parameters: array_merge(array_values($values), $parameter));
    }

    /**
     * Id posledního vložení do DB
     * @return int
     */
    public static function latestId() : int
    {
        return Database::$db->lastInsertId();
    }


}