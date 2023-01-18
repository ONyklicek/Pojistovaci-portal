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

class InsuredModel extends DbModel
{
    public function getInsured(string $id)
    {
        return self::selectOne("SELECT * 
                                    FROM `users` 
                                    WHERE `user_type` = 1 AND `user_id` = ? "
            , array($id));

    }

    /**
     * Aktualizace uživatele
     * @param int $id ID uživatele
     * @param array $data Data z formuláře
     * @return void
     */
    public function updateInsured(int $id, array $data): void
    {
        if(!empty($data['user_password'])) {
            if ($data['user_password'] != $data['user_passwordConfirm'])
                throw new \Exception('Hesla nesouhlasí.');
            if (!filter_var($data['user_email'], FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('Neplatný formát emailu.');
            }
        }

        $user = [
            'user_firstname' => $data['user_firstname'],
            'user_lastname' => $data['user_lastname'],
            'user_email' => $data['user_email'],
            'user_birthdate' => $data['user_birthdate'],
            'user_telephone' => $data['user_telephone'],
            'user_city' => $data['user_city'],
            'user_address' => $data['user_address'],
            'user_psc' => $data['user_psc'],
            'user_password' => $this->passwordHash($data['user_password'])
        ];
        try {
            self::update('users', $user, 'WHERE user_id = ?', array($id));
        } catch (\PDOException $error){
            throw new \Exception('Při zpracování nastala chyba: ' . $error->getMessage());
        }
    }
}