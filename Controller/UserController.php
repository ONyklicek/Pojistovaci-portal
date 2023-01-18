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

class UserController extends Controller
{
    /**
     * Výpis uživatele
     * @param Request $request
     * @return array|string|string[]|void
     */
    public function user(Request $request):array|string|false
    {
        $head = [
            'title' => 'Uživatel'
        ];

        $requestId = $request->getRouteParam('id');
        $userModel = new UserModel();
        $userData = $userModel->getUser($requestId);

        if($requestId){
            if(empty($userData)){
                Application::$app->response->redirect('/404');
            } else {
                return self::render(__FUNCTION__, $head, $userData);
            }
        }
    }

    /**
     * Výpis všech uživatelů
     * @return array|string|string[]
     */
    public function users(): array|string
    {
        $head = [
            'title' => "Uživatelé"
        ];
        $userModel = new UserModel();
        $data = $userModel->getUsers();

        return self::render(__FUNCTION__, $head,  $data);
    }

    public function editUser(Request $request)
    {
        $requestId = $request->getRouteParam('id');

        if((Application::isAdmin() OR ('user' === 'user'))) {
            if ($request->isGet()) {
                if (isset($requestId)) {
                    $head = [
                        'title' => 'Editace uživatele',
                    ];

                    $userModel = new UserModel();
                    $data = $userModel->getUser($requestId);

                    return self::render(__FUNCTION__, $head, $data);
                } else {
                    Application::$app->response->redirect("/users");
                }
            } elseif ($request->isPost()) {
                try {
                    $userModel = new UserModel();
                    $formData = $request->getBody();

                    if (isset($requestId)) {
                        $head = [
                            'title' => 'Editace uživatele',
                        ];
                        $userModel->updateUser($requestId, $formData);
                        Application::$app->session->setFlash('success', 'Uživatel byl úspěšně aktualizován');
                        Application::$app->response->redirect("/users");
                    } else {
                        $head = [
                            'title' => 'Přidání nového uživatele',
                        ];
                        $newId = $userModel->addUser($formData);
                        Application::$app->session->setFlash('success', 'Uživatel byl úspěšně pridán');
                        Application::$app->response->redirect("/users");
                    }

                } catch (\Exception $e) {
                    Application::$app->session->setFlash('warning', $e->getMessage());
                    return self::render(__FUNCTION__, $head, $formData);
                }
            }
        }
    }

    /**
     * Přidání uživatele
     * @param Request $request
     * @return array|string|string[]|void
     */
    public function addUser(Request $request)
    {
        if(Application::isAdmin()) {
            if ($request->isPost()) {
                try {
                    $userModel = new UserModel();
                    $formData = $request->getBody();

                    $head = [
                        'title' => 'Přidání nového uživatele',
                    ];

                    $userModel->addUser($formData);
                    Application::$app->session->setFlash('success', 'Uživatel byl úspěšně přidán');
                    Application::$app->response->redirect("/users");


                } catch (\Exception $e) {
                    Application::$app->session->setFlash('warning', $e->getMessage());
                    return self::render(__FUNCTION__, $head, $formData);
                }
            } else {
                $head = [
                    'title' => 'Přidání nového uživatele',
                ];

                return self::render(__FUNCTION__, $head, []);
            }
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
        $userModel = new UserModel();
        $userModel->deleteUser($request->getRouteParam('id'));

        Application::$app->session->setFlash('success', 'Uživatel byl úspěšně odstraněn.');
        Application::$app->response->redirect('/users');
    }
}