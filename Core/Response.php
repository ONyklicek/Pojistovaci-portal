<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Core;

class Response
{
    public function statusCode(int $code)
    {
        http_response_code($code);
    }

    public function redirect($url)
    {
        header("Location: $url");
    }
}