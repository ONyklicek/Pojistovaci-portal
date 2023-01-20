<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Controller;

use App\Core\Controller;
use App\Core\Application;
use App\Core\Request;
use App\Model\InsuranceModel;
use App\Model\InsuredModel;

class InsuranceController extends Controller
{

    /**
     * Výpis pojištění
     * @param Request $request
     * @return string
     */
    public function insurance(Request $request): string
    {
        $head = [
            'title' => 'Detail pojištění'
        ];
        $insuredModel = new InsuranceModel();
        $data = $insuredModel->getInsurance($request->getRouteParam('id'));



        $perm = (!Application::isAdmin() || ($request->getUserId() == $insuredModel->getUserIdInsurance($request->getRouteParam('id'))['user_id']));

        if(!Application::isAdmin() && $request->getUserId() != $insuredModel->getUserIdInsurance($request->getRouteParam('id'))['user_id']) {
            Application::$app->response->redirect('/404');
        }
        return self::render(__FUNCTION__, $head, $data);
    }

    /**
     * Výpis všech pojištění
     * @param Request $request
     * @return string
     */
    public function insurances(Request $request): string
    {
        $insuredModel = new InsuranceModel();

        $head = [
            'title' => 'Sjednaná pojištěšní'
        ];

        if(Application::isAdmin()){
            $data = $insuredModel->getAllInsurances();
            return self::render(__FUNCTION__, $head, $data);
        }
        $data = $insuredModel->getUserInsurances($request->getUserId());
        return self::render(__FUNCTION__, $head, $data);
    }

    /**
     * Přidání pojištění
     * @param Request $request
     * @return string
     */
    public function addInsurance(Request $request): string

    {
        $head = [
            'title' => 'Sjednání nového pojištění'
        ];

        $userId['user_id'] = $request->getUserId();
        bdump($userId);

        $insuredModel = new InsuranceModel();
        $productData = $insuredModel->getActiveProducts();

        if($request->isPost()){
            try {
                $data = array_merge($request->getBody(), $userId);
                $insuredModel->addInsurance($data);

                Application::$app->session->setFlash('success', 'Pojištění bylo úspěšně sjednáno');
                Application::$app->response->redirect('/insurances');
            } catch (\Exception $e){
                $data = $request->getBody();
                $data['DB'] = $productData;

                Application::$app->session->setFlash('warning', $e->getMessage());

                return self::render(__FUNCTION__, $head, $data);
            }
        }

        return self::render(__FUNCTION__, $head, $productData);
    }

    /**
     * Editace pojištění
     * @param Request $request
     * @return string
     */
    public function editInsurance(Request $request): string

    {
        $head = [
          'title' => 'Editace pojištění'
        ];

        $insuredModel = new InsuranceModel();

        if(!Application::isAdmin() && $request->getUserId() != $insuredModel->getUserIdInsurance($request->getRouteParam('id'))['user_id']) {
            Application::$app->session->setFlash('warning', 'Pro editaci tohoto pojistění nemáte dostatečná oprávnění.');
            Application::$app->response->redirect('/insurances');
        }

        if ($request->isPost()) {
                try {
                    $formData = $request->getBody();
                    $insuredModel->updateInsurance($request->getRouteParam('id'), $formData);
                    Application::$app->response->redirect('/insurance/'.$request->getRouteParam('id'));

                } catch (\Exception $e){
                    Application::$app->session->setFlash('warning', $e->getMessage());
                    return self::render(__FUNCTION__, $head, $formData);
                }
            }

        $data = $insuredModel->getInsurance($request->getRouteParam('id'));
        return self::render(__FUNCTION__, $head, $data);
    }

    /**
     * Odstranění pojištění
     * @param Request $request
     * @return void
     */
    public function deleteInsurance(Request $request): void
    {
        $insuredModel = new InsuranceModel();

        if(!Application::isAdmin() && $request->getUserId() != $insuredModel->getUserIdInsurance($request->getRouteParam('id'))['user_id']) {
            Application::$app->session->setFlash('warning', 'Pro odstranění tohoto pojistění nemáte dostatečná oprávnění.');
        } else {
            $insuredModel->deleteInsurance($request->getRouteParam('id'));

            Application::$app->session->setFlash('success', 'Pojistění bylo úspěšně odstraněno.');
        }
        Application::$app->response->redirect('/insurances');
    }



}
