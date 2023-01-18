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
    public function insurance(Request $request)
    {
        #TODO - detail pojištění
    }

    /**
     * Výpis všech pojištění
     * @param Request $request
     * @return array|string
     */
    public function insurances(Request $request): array|string
    {
        $insuredModel = new InsuranceModel();

        $head = [
            'title' => 'Sjednaná pojištěšní'
        ];

        if(Application::isAdmin()){
            $data = $insuredModel->getAllInsurance();
            return self::render(__FUNCTION__, $head, $data);
        }
        $data = $insuredModel->getUserInsurance($request->getUserId());

        return self::render(__FUNCTION__, $head, $data);
    }

    /**
     * Přidání pojištění
     * @param Request $request
     * @return array|string|string[]
     */
    public function addInsurance(Request $request): array|string
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

    public function editInsurance(Request $request)
    {
        #TODO - editace pojištšní
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
            Application::$app->response->redirect('/insurances');
        } else {
            $insuredModel->deleteInsurance($request->getRouteParam('id'));

            Application::$app->session->setFlash('success', 'Pojistění bylo úspěšně odstraněno.');
            Application::$app->response->redirect('/insurances');
        }
    }



}