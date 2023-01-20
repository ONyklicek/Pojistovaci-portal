<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Core;

use DateTime;

class Helper
{
    /**
     * Formát data dd.mm.yyyy
     * @param  string $date vstupní datum
     * @return string
     */
    public static function date(string $date): ?string
    {
        if (!$date) {
            return '';
        } elseif ($date === '0000-00-00') {
            return '';
        }
        return date('d.m.Y', strtotime($date));
    }

    /**
     * Dlouhý název měsíce
     * @param  string $date vstuní datum
     * @return string
     */
    public static function longDate(string $date): string
    {
        if (!$date) {
            return '';
        }

        $czMonths = ['ledna', 'února', 'března', 'dubna', 'května', 'června', 'července', 'srpna', 'září', 'října', 'listopadu', 'prosince'];

        $monthNumber = intval(date('n', strtotime($date)));

        return sprintf('%d.%s. %d',
            date('j', strtotime($date)),
            $czMonths[$monthNumber],
            date('Y', strtotime($date)));
    }

    /**
     * Ověření nastavenáho datumu
     * @param string $date
     * @return string|null
     */
    public static function dateIsSet(string $date): ?string
    {
        if ($date == '0000-00-00') {
            return '';
        } else{
            return $date;
        }
    }

    /**
     * Převod datunu na věk
     * @param string $date vstupní datum narození
     * @return string|null
     */
    public static function dateToOld(string $date): ?string
    {
        if ($date == '0000-00-00') {
            return null;
        } else {

            $today = date("Y-m-d");
            $diff = date_diff(date_create($date), date_create($today));
            return $diff->format('%y');
        }
    }

    public static function currentDate($setDay = ''): string
    {
        $date = new DateTime($setDay);

        return $date->format('Y-m-d');
    }

    public static function productEnd($endDate): string
    {
        if($endDate < self::currentDate())
            return 'Vyplšela';

        $diff = date_diff(date_create(self::currentDate()), date_create($endDate));
        return $diff->format('%a dní');
    }
}