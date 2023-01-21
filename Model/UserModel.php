<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Model;

use App\Core\Application;
use App\Core\Database\DbModel;
use App\Core\Messages\eMsgUser;
use http\Exception;

class UserModel extends DbModel
{
    /**
     * Výpis všech uživatelů
     * @return array|bool
     */
    public function getUsers(): array|bool
    {
        return self::selectAll("SELECT * FROM users ORDER BY `user_id`");
    }

    /**
     * Výpis skupiny uživatelů
     * @param int $id ID skupiny uživatelů
     * @return array|bool
     */
    public function getUsersGroup(int $id): array|bool
    {
        return self::selectAll("SELECT `user_id`,`user_email`,`user_firstname`,`user_lastname`,`user_address`, `user_city`, `user_psc` 
                                    FROM `users` 
                                    WHERE `user_type` = ? 
                                    ORDER BY `user_id`"
                        , array($id));
    }

    /**
     * Výpis uživatele dle ID
     * @param int $id ID uživatele
     * @return array|bool
     */
    public function getUser(int $id): array|bool
    {
        return self::selectOne("SELECT * 
                                    FROM users
                                    WHERE user_id = ? "
            , array($id));
    }

    /**
     * Přihlášení uživatele
     * @param string $login Email nebo tel. číslo uživatele
     * @param string $password Heslo
     * @return void
     * @throws \Exception
     */
    public function login(string $login, string $password): void
    {
        if(empty($login)) {
            throw new \Exception(eMsgUser::ERR_INVALID_LOGIN->value);
        }
        bdump($password);
        if(empty($password)) {
            throw new \Exception(eMsgUser::ERR_PASS_EMPTY->value);
        }

        $user = self::selectOne('
			SELECT user_id, user_email, user_telephone, user_firstname, user_lastname, user_password , user_type
			FROM users
			WHERE user_email = ? OR user_telephone  = ?
		', array($login, $login));
        if (!$user){
            throw new \Exception(eMsgUser::ERR_INVALID_LOGIN->value);

        } elseif (!password_verify($password, $user['user_password'])){
            throw new \Exception(eMsgUser::ERR_INVALID_PASS->value);
        }
        Application::$app->session->set(key: 'user' , value: $user);
    }

    /**
     * Registrace nového uživatele
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public function addUser(array $data) : void
    {
        self::userFormValid($data);

        $user = array(
            'user_firstname' => $data['user_firstname'],
            'user_lastname' => $data['user_lastname'],
            'user_email' => $data['user_email'],
            'user_birthdate' => $data['user_birthdate'],
            'user_telephone' => $data['user_telephone'],
            'user_city' => $data['user_city'],
            'user_address' => $data['user_address'],
            'user_psc' => $data['user_psc'],
            'user_type' => $data['user_type'],
            'user_password' => $this->passwordHash($data['user_password'])
        );

        try {
            self::insert('users', $user);
        }catch (\PDOException $error){
            throw new \Exception('Při zpracování nastala chyba: ' . $error->getMessage());
        }
    }

    /**
     * Registrace uživatele
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public function register(array $data) : void
    {

        self::userFormValid($data);

        $user = array(
            'user_firstname' => $data['user_firstname'],
            'user_lastname' => $data['user_lastname'],
            'user_email' => $data['user_email'],
            'user_birthdate' => $data['user_birthdate'],
            'user_telephone' => $data['user_telephone'],
            'user_city' => $data['user_city'],
            'user_address' => $data['user_address'],
            'user_psc' => $data['user_psc'],
            'user_password' => $this->passwordHash($data['user_password'])
        );

        try {
            self::insert('users', $user);
        }catch (\PDOException $error){
            throw new \Exception('Při zpracování nastala chyba: ' . $error->getMessage());
        }
    }

    /**
     * Aktualizace uživatele
     * @param int $id ID uživatele
     * @param array $data Data z formuláře
     * @return void
     * @throws \Exception
     */
    public function updateUser(int $id, array $data): void
    {
        if(!empty($data['user_password'])) {
            if ($data['user_password'] != $data['user_passwordConfirm']) {
                throw new \Exception(eMsgUser::ERR_PASS_EMPTY->value);
            }
            if (!filter_var($data['user_email'], FILTER_VALIDATE_EMAIL)) {
                throw new \Exception(eMsgUser::ERR_INVALID_FORMAT_EMAIL->value);
            }
        }
        if (!self::validPhoneNumber($data['user_telephone'])) {
            throw new \Exception(eMsgUser::ERR_INVALID_FORMAT_PHONE->value);
        }

        $dbKey = array('user_firstname', 'user_lastname', 'user_email', 'user_birthdate', 'user_telephone', 'user_city', 'user_address', 'user_psc', 'user_type');
        $userData = array_intersect_key($data, array_flip($dbKey));

        try {
            self::update('users', $userData, 'WHERE user_id = ?', array($id));
        } catch (\PDOException $error){
            throw new \Exception('Při zpracování nastala chyba: ' . $error->getMessage());
        }
    }

    /**
     * Odstranění uživatele
     * @param int $id
     * @return void
     */
    public function deleteUser(int $id): void
    {
        self::query('DELETE FROM users 
                        WHERE user_id = ? ', array($id));
    }

    /**
     * Zahashování hesla
     * @param string $password
     * @return string
     */
    public function passwordHash(string $password) : string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Ověření telefoního čísla
     * @param string $phoneNumber Telefoní číslo
     * @return $this|void
     */
    public function validPhoneNumber(string $phoneNumber)
    {
        $pattern = '~^(\+420)? ?\d{3} ?\d{3} ?\d{3}$~';

        if (preg_match($pattern, $phoneNumber)) {

            bdump($this);
            return $this;
        }

    }

    /**
     * @param array $data
     * @return void
     * @throws \Exception
     */
    protected function userFormValid(array $data): void
    {
        if (empty($data['user_password']) or empty($data['user_passwordConfirm']))
            throw new \Exception(eMsgUser::ERR_PASS_EMPTY->value);
        if ($data['user_password'] != $data['user_passwordConfirm'])
            throw new \Exception(eMsgUser::ERR_PASS_NOT_MATCH->value);
        if (!filter_var($data['user_email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception(eMsgUser::ERR_INVALID_EMAIL->value);
        }
        if (!self::validPhoneNumber($data['user_telephone'])) {
            throw new \Exception(eMsgUser::ERR_INVALID_FORMAT_PHONE->value);
        }
    }


}