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
use App\Core\ArrayHelper;
use App\Core\Controller;
use App\Core\Request;
use App\Model\SiteModel;

class SiteController extends Controller
{
    /**
     * Úvodní stránka
     * @return string
     */
    public function home(Request $request): string
    {
        self::isLogged();

        $head = [
            'title' => 'Dashboard'
        ];

        $siteModel = new SiteModel();
        if (Application::isAdmin()) {

            $data = array_merge(
                $siteModel->adminDashboard(),
                ArrayHelper::arrayPrefixed('end_insurances', $siteModel->adminDashboardExpiration())
            );
            return self::render('adminDashboard', $head, $data);
        }

        $data = array_merge(
            $siteModel->userDashboard($request->getUserId()),
            ArrayHelper::arrayPrefixed('end_insurances', $siteModel->userDashboardExpiration($request->getUserId()))
        );
        return self::render('userDashboard', $head, $data);
    }
}
