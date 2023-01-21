<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Model;

use App\Core\Database\DbModel;

class SiteModel extends DbModel
{
    /**
     * Výpis statistik admin dash
     * @return false|array
     */
    public function adminDashboard(): false|array
    {
        return self::selectOne(sql: '
                SELECT  (
                    SELECT COUNT(*)
                    FROM   insurances
                ) AS tot_count_insurances,
                (    SELECT COUNT(*)
                     FROM  insurances
                     WHERE insurance_end_date >= CURDATE()
                )AS tot_active_insurances,
                (
                    SELECT COUNT(*)
                    FROM   users
                    WHERE user_type = 1
                ) AS tot_count_ins,
                (
                    SELECT SUM(insurance_sum)
                    FROM insurances
                ) AS tot_su_insurances
        ');
    }

    public function adminDashboardExpiration(): false|array
    {
        return self::selectAll('
            SELECT u.user_firstname, u.user_lastname, p.product_name, insurance_id, insurance_sum, insurance_end_date
            FROM insurances
            INNER JOIN products p on insurances.product_id = p.product_id
            INNER JOIN users u on insurances.user_id = u.user_id
            WHERE insurance_end_date >= CURDATE()
            ORDER BY insurance_end_date ASC 
            LIMIT 5
        ');

    }
}