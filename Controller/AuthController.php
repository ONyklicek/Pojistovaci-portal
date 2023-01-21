<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Controller;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Messages\eMsgUser;
use App\Core\Request;
use App\Model\UserModel;
use JetBrains\PhpStorm\NoReturn;

class AuthController extends Controller
{
    /**
     * Přihlášení uživatele
     * @param Request $request
     * @return array|string
     */
    public function login(Request $request): array|string
    {
        $head = [
            'title' => 'Přihlášení'
        ];

        if($request->isPost()) {
            $loginUser = new UserModel();
            $formData = $request->getBody();

            try {
                $loginUser->login(
                    $formData['login'],
                    $formData['password'],
                );
                Application::$app->session->setFlash('success', eMsgUser::MSG_LOGIN->value);
                Application::$app->response->redirect("/");
            } catch (\Exception $e) {
                Application::$app->session->setFlash('warning', $e->getMessage());
                return self::render(__FUNCTION__, $head, $formData);
            }
        }
        return self::render(__FUNCTION__, $head, []);
    }

    /**
     * Odhlášení uživatele
     * @return void
     */
    #[NoReturn] public function logout(): void
    {
        Application::$app->session->remove('user');
        Application::$app->session->setFlash('success', eMsgUser::MSG_LOGOUT->value);
        Application::$app->response->redirect('/');
    }

    /**
     * Registrace uživatele
     * @param Request $request
     * @return array|string
     */
    public function register(Request $request): array|string
    {
        $head = [
            'title' => 'Registrace'
        ];
        $registerUser = new UserModel();
        if($request->isPost()) {
            $formData = $request->getBody();
            try {
                $registerUser->register($formData);

                Application::$app->session->setFlash('success', eMsgUser::MSG_REGISTER->value);
                Application::$app->response->redirect("/login");
            } catch (\Exception $e) {
                Application::$app->session->setFlash('warning', $e->getMessage());
                return $this->render(__FUNCTION__, $head, $formData);
            }
        }
        return $this->render(__FUNCTION__, $head,  []);
    }
}
