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
use App\Model\UserModel;

class InsuredController extends Controller
{
    /**
     * Výois pojištěnců
     * @return array|string
     */
    public function insureds(): array|string
    {
        $head = [
            'title' => "Pojištěnci"
        ];
        $policyholdersModel = new UserModel();
        $data = $policyholdersModel->getUsersGroup("1");

        return self::render(__FUNCTION__, $head,  $data);
    }
}
