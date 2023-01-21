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
use App\Model\SiteModel;

class SiteController extends Controller
{
    /**
     * Úvodní stránka
     * @return string
     */
    public function home(Request $request): string
    {
        if($request->isLogged()) {
            if (Application::isAdmin()) {
                $head = [
                    'title' => 'Dashboard'
                ];

                $siteModel = new SiteModel();
                $data = $siteModel->adminDashboard();
                $data['end_insurances'] = $siteModel->adminDashboardExpiration();
                bdump($data);

                return self::render('adminDashboard', $head, $data);
            }

        }

        return self::render('welcome');
    }
}