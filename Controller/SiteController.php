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

class SiteController extends Controller
{


    /**
     * Úvodní stránka
     * @return string|
     */
    public function home(): string
    {
        if(Application::$app->session->get('user')){
            return self::render(__FUNCTION__);
        }

        return self::render('welcome');
    }
}