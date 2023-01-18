<?php
/**
 * Class Application - Pojištěnci
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Controller;

use App\Core\Controller;
use App\Core\Request;
use App\Model\InsuredModel;
use App\Model\UserModel;

class InsuredController extends Controller
{
    public function insureds()
    {
        $head = [
            'title' => "Pojištěnci"
        ];
        $policyholdersModel = new UserModel(); //new PolicyholdersModel();
        $data = $policyholdersModel->getUsersGroup("1");

        return self::render(__FUNCTION__, $head,  $data);
        
    }

    /**
     * Výpis pojištěného
     * @param Request $request
     * @return array|string|string[]
     */
    public function insured(Request $request): array|string
    {
        $head = [
            'title' => 'Detail pojištěnce'
        ];

        $requestId = $request->getRouteParam('id');
        $policyholdersModel = new InsuredModel();
        $data = $policyholdersModel->getInsured($requestId);

        return self::render('user', $head,  $data);
    }

    public function editInsured(Request $request, string $id)
    {
        $requestId = $request->getRouteParam('id');

        #TODO - editace pojistěn
    }

    public function deleteInsured(Request $request, string $id)
    {
        #TODO - odstranění pojištěnce
    }
}