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
     * @return array|string|string[]|void
     */
    public function insurance(Request $request)
    {
        $head = [
            'title' => 'Detail pojištění'
        ];
        $insuredModel = new InsuranceModel();
        $data = $insuredModel->getInsurance($request->getRouteParam('id'));

        bdump($data);


        if(Application::isAdmin() OR $request->getUserId() == $insuredModel->getUserIdInsurance($request->getRouteParam('id'))['user_id']) {
            return self::render(__FUNCTION__, $head, $data);
        } else {
            echo 'Nejsi oprávněný';
        }
    }

    /**
     * Výpis všech pojištění
     * @param Request $request
     * @return array|string
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
     * @return array|string|string[]
     */
    public function addInsurance(Request $request)
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

    public function editInsurance(Request $request): mixed
    {
        $head = [
          'title' => 'Editace pojištění'
        ];

        $insuredModel = new InsuranceModel();

        if(Application::isAdmin() OR $request->getUserId() == $insuredModel->getUserIdInsurance($request->getRouteParam('id'))['user_id']) {
            if ($request->isPost()) {
                try {
                    $formData = $request->getBody();
                    $insuredModel->updateInsurance($request->getRouteParam('id'), $formData);
                    Application::$app->response->redirect('/insurance/'.$request->getRouteParam('id'));


                } catch (\Exception $e){
                    Application::$app->session->setFlash('warning', $e->getMessage());
                    return self::render(__FUNCTION__, $head, $formData);

                }
            } else {
                $data = $insuredModel->getInsurance($request->getRouteParam('id'));
                return self::render(__FUNCTION__, $head, $data);
            }
        } else {
            Application::$app->session->setFlash('warning', 'Pro editaci tohoto pojistění nemáte dostatečná oprávnění.');
            Application::$app->response->redirect('/insurances');
        }


    }

    /**
     * Odstranění pojištění
     * @param Request $request
     * @return void
     */
    public function deleteInsurance(Request $request): void
    {
        $insuredModel = new InsuranceModel();

        if(!Application::isAdmin() OR $request->getUserId() != $insuredModel->getUserIdInsurance($request->getRouteParam('id'))['user_id']) {
            Application::$app->session->setFlash('warning', 'Pro odstranění tohoto pojistění nemáte dostatečná oprávnění.');
        } else {
            $insuredModel->deleteInsurance($request->getRouteParam('id'));

            Application::$app->session->setFlash('success', 'Pojistění bylo úspěšně odstraněno.');
        }
        Application::$app->response->redirect('/insurances');
    }



}
