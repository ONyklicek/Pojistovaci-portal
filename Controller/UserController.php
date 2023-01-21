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
use App\Core\Request;
use App\Model\UserModel;
use JetBrains\PhpStorm\NoReturn;

class UserController extends Controller
{
    /**
     * Výpis uživatele
     * @param Request $request
     * @return array|string
     */
    public function user(Request $request): array|string
    {
        self::isLogged();

        $head = [
            'title' => 'Uživatel'
        ];

        $userModel = new UserModel();
        $userData = $userModel->getUser($request->getRouteParam('id'));

        if(!Application::isAdmin() && $request->getRouteParam('id') != $request->getUserId()) {
            Application::$app->response->redirect('/user/' . $request->getUserId());

        }
        return self::render(__FUNCTION__, $head, $userData);
    }


    /**
     * Výpis všech uživatelů
     * @return array|string
     */
    public function users(): array|string
    {
        self::isLogged();

        $head = [
            'title' => "Uživatelé"
        ];
        $userModel = new UserModel();
        $data = $userModel->getUsers();

        if(!Application::isAdmin()) {
            Application::$app->response->redirect('/404');
        }
        return self::render(__FUNCTION__, $head, $data);
    }

    /**
     * Výois pojištěnců
     * @return array|string
     */
    public function insureds(): array|string
    {
        self::isLogged();

        $head = [
            'title' => "Pojištěnci"
        ];
        $policyholdersModel = new UserModel();
        $data = $policyholdersModel->getUsersGroup(1);

        return self::render(__FUNCTION__, $head,  $data);
    }

    /**
     * Editace uživatele
     * @param Request $request
     */
    public function editUser(Request $request)
    {

        self::isLogged();

        $head = [
            'title' => 'Editace uživatele',
        ];

        $userModel = new UserModel();

        if(Application::isAdmin() OR $request->getRouteParam('id') == $request->getUserId()) {
            if ($request->isPost()) {
                $formData = $request->getBody();
                try {
                    $userModel->updateUser($request->getRouteParam('id'), $formData);

                    if(Application::isAdmin()) {
                        Application::$app->session->setFlash('success', 'Uživatel byl úspěšně aktualizován');

                        Application::$app->response->redirect("/users");
                    } else {
                        Application::$app->session->setFlash('success', 'Údaje byly úspěšně aktualizovány');
                        Application::$app->response->redirect("/user/".$request->getUserId());
                    }
                } catch (\Exception $e) {
                    Application::$app->session->setFlash('warning', $e->getMessage());
                    return self::render(__FUNCTION__, $head, $formData);
                }
            }
            $data = $userModel->getUser($request->getRouteParam('id'));

            return self::render(__FUNCTION__, $head, $data);
        }
    }

    /**
     * Přidání uživatele
     * @param Request $request
     */
    public function addUser(Request $request)
    {
        self::isLogged();

        $head = [
            'title' => 'Přidání nového uživatele',
        ];

        $userModel = new UserModel();
        $formData = $request->getBody();



        if(Application::isAdmin()) {
            if ($request->isPost()) {
                try {
                    $userModel->addUser($formData);
                    Application::$app->session->setFlash('success', 'Uživatel byl úspěšně přidán');
                    Application::$app->response->redirect("/users");
                } catch (\Exception $e) {
                    Application::$app->session->setFlash('warning', $e->getMessage());
                    return self::render(__FUNCTION__, $head, $formData);
                }
            }
            return self::render(__FUNCTION__, $head, []);

        } else {
            Application::$app->session->setFlash('warning', 'Nemáte dostatečné oprávnění pro přidání uživatele');
            Application::$app->response->redirect('/user/'.$request->getUserId());
        }
    }

    /**
     * Odstranění uživatele
     *
     * @param Request $request
     * @return void
     */
    public function deleteUser(Request $request): void
    {
        self::isLogged();

        $userModel = new UserModel();
        $userModel->deleteUser($request->getRouteParam('id'));

        Application::$app->session->setFlash('success', 'Uživatel byl úspěšně odstraněn.');
        Application::$app->response->redirect('/users');
    }
}