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
class SiteController extends Controller
{


    /**
     * @return array|string|string[]|void
     */
    public function home()
    {
        if(Application::$app->session->get('user')){
            return self::render(__FUNCTION__);
        } else {
            return self::render('welcome');
        }
    }



}