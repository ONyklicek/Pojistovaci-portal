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
use App\Core\Helper;

class InsuranceModel extends DbModel
{
    public function getActiveProducts(): array
    {
        return self::selectAll("SELECT product_id, product_name FROM products WHERE product_active = true ORDER BY product_id");
    }

    public function getAllInsurance(): array
    {
        return self::selectAll("SELECT u.user_lastname, u.user_firstname, p.product_name, insurance_end_date, insurance_id
                                    FROM insurances 
                                    INNER JOIN users u on insurances.user_id = u.user_id
                                    INNER JOIN products p on insurances.product_id = p.product_id
                                    ORDER BY insurance_id DESC");
    }

    public function getUserInsurance(int $id): array
    {
        return self::selectAll("SELECT u.user_lastname, u.user_firstname, p.product_name, insurance_end_date, insurance_id
                                    FROM insurances 
                                    INNER JOIN users u on insurances.user_id = u.user_id
                                    INNER JOIN products p on insurances.product_id = p.product_id
                                    WHERE u.user_id = ?
                                    ORDER BY insurance_id DESC", array($id));
    }

    /**
     * Získání uživatelského ID pojištění
     * @param int $id ID pojištění
     * @return array
     */
    public function getUserIdInsurance(int $id): array|int
    {
        bdump($id);
        return self::selectOne("SELECT user_id 
                                    FROM insurances 
                                    WHERE insurance_id = ? ", array($id));
    }

    public function addInsurance(array $data): void
    {
        if ($data['insurance_sum'] == '0'){
            throw new \Exception('Částka nesmí být nula');
        } elseif (empty($data['insurance_sum'])){
            throw new \Exception('Částka nesmí být prázdná.');
            }

        if((empty($data['insurance_start_date'])) OR (empty($data['insurance_end_date']))){
            throw new \Exception('Datum nesmí být prázdné');
        } elseif ($data['insurance_start_date'] < Helper::currentDate()){
            throw new \Exception('Datum nesmí být v minulosti');
        } elseif ($data['insurance_end_date'] <= Helper::currentDate()){
            throw new \Exception('Nastavte alepsoň zítřejší datum');
        }

        try {
            self::insert('insurances', $data);
        } catch (\PDOException $error){
            throw new \Exception('Při zpracování nastala chyba: ' . $error->getMessage());
        }
    }

    /**
     * Odstranění pojištění
     * @param ind $id
     * @return void
     */
    public function deleteInsurance(int $id): void
    {
        self::query('DELETE FROM insurances 
                        WHERE insurance_id = ? ', array($id));
    }
}