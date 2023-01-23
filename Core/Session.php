<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Core;

use App\Model\UserModel;

class Session
{
    protected const FLASH_KEY = 'flash_messages';

    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }


    /**
     * Nastavení sessionu
     * @param $key
     * @param string|array $value
     * @return void
     */
    public function set($key, string|array $value)
    {
            $_SESSION[$key] = $value;
    }


    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function __destruct()
    {
        $this->removeFlashMessages();
    }

    private function removeFlashMessages()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => $flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function sessionUserValid()
    {
        $userModel = new UserModel();
        $userData = $userModel->sessionValidData(Application::$app->request->getUserId());

        bdump($userData);
        bdump(self::get('user')['user_email']);
        bdump($userData['user_email']);

        foreach ($userData as $key => $value){
            if(self::get('user')[$key] != $userData[$key]){
                $_SESSION['user'][$key] = $value;
            }
        }
    }

}