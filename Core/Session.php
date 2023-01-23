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
    public function set($key, string|array $value): void
    {
            $_SESSION[$key] = $value;
    }

    public function get($key): mixed
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove($key): void
    {
        unset($_SESSION[$key]);
    }

    public function __destruct()
    {
        $this->removeFlashMessages();
    }

    private function removeFlashMessages(): void
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => $flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function sessionUserValid(): void
    {
        if(isset($_SESSION['user'])){
            $userModel = new UserModel();
            $userData = $userModel->sessionValidData(Application::$app->request->getUserId());

            foreach ($userData as $key => $value){
                if(self::get('user')[$key] != $value){
                    $_SESSION['user'][$key] = $value;
                }
            }
        }
    }

}