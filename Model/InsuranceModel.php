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
    /**
     * Výpis aktivních produktů
     * @return array
     */
    public function getActiveProducts(): array
    {
        return self::selectAll("SELECT product_id, product_name FROM products WHERE product_active = true ORDER BY product_id");
    }

    /**
     * Výpis pojištění
     * @param int $id
     * @return false|array
     */
    public function getInsurance(int $id): false|array
    {
        return self::selectOne("SELECT u.user_lastname, u.user_firstname, p.product_name, insurance_start_date, insurance_end_date, insurance_sum, insurance_id
                                    FROM insurances 
                                    INNER JOIN users u on insurances.user_id = u.user_id
                                    INNER JOIN products p on insurances.product_id = p.product_id
                                    WHERE insurance_id = ? ", array($id));
    }

    /**
     * Výpis všech pojištění
     * @return array
     */
    public function getAllInsurances(): array
    {
        return self::selectAll("SELECT u.user_lastname, u.user_firstname, p.product_name, insurance_end_date, insurance_sum, insurance_id
                                    FROM insurances 
                                    INNER JOIN users u on insurances.user_id = u.user_id
                                    INNER JOIN products p on insurances.product_id = p.product_id
                                    ORDER BY insurance_id");
    }

    /** Výpis pojištění dle ID uživatele
     * @param int $id ID uživatele
     * @return array
     */
    public function getUserInsurances(int $id): array
    {
        return self::selectAll("SELECT u.user_lastname, u.user_firstname, p.product_name, insurance_end_date, insurance_sum, insurance_id
                                    FROM insurances 
                                    INNER JOIN users u on insurances.user_id = u.user_id
                                    INNER JOIN products p on insurances.product_id = p.product_id
                                    WHERE u.user_id = ?
                                    ORDER BY insurance_id", array($id));
    }

    /**
     * Získání uživatelského ID pojištění
     * @param int $id ID pojištění
     * @return false|array
     */
    public function getUserIdInsurance(int $id): false|array
    {
        return self::selectOne("SELECT user_id 
                                    FROM insurances 
                                    WHERE insurance_id = ? ", array($id));
    }

    /**
     * Vytvoření pojištění
     * @param array $data data z formuláře
     * @return void
     * @throws \Exception
     */
    public function addInsurance(array $data): void
    {
        self::validInsurance($data);

        try {
            self::insert('insurances', $data);
        } catch (\PDOException $error) {
            throw new \Exception('Při zpracování nastala chyba: ' . $error->getMessage());
        }
    }

    /**
     * Aktualizace pojištění
     * @param int $id
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public function updateInsurance(int $id, array $data): void
    {
        self::validInsurance($data);

        $dbKey = array('insurance_sum', 'insurance_start_date', 'insurance_end_date');
        $dataInsurance = array_intersect_key($data, array_flip($dbKey));

        try {
            self::update('insurances', $dataInsurance, 'WHERE insurance_id = ?', array($id));
        } catch (\PDOException $error) {
            throw new \Exception('Při zpracování nastala chyba: ' . $error->getMessage());
        }
    }

    /**
     * Odstranění pojištění
     * @param int $id
     * @return void
     */
    public function deleteInsurance(int $id): void
    {
        self::query('DELETE FROM insurances 
                        WHERE insurance_id = ? ', array($id));
    }

    /**
     * Validace pojištění
     * @param array $data
     * @return void
     * @throws \Exception
     */
    protected function validInsurance(array $data): void
    {
        if ($data['insurance_sum'] == '0'){
            throw new \Exception('Částka nesmí být nula');
        } elseif (empty($data['insurance_sum'])){
            throw new \Exception('Částka nesmí být prázdná.');
        }

        if((empty($data['insurance_start_date'])) OR (empty($data['insurance_end_date']))){
            throw new \Exception('Datum nesmí být prázdné');
        } elseif (Helper::currentDate() > $data['insurance_start_date']){
            throw new \Exception('Datum nesmí být v minulosti');
        } elseif (Helper::currentDate() > $data['insurance_end_date']){
            throw new \Exception('Nastavte alepsoň zítřejší datum');
        }
    }
}
