<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Core;

use JetBrains\PhpStorm\NoReturn;

class Response
{
    /**
     * Nastavení HTTP status kodu
     * @param int $code
     * @return void
     */
    public function statusCode(int $code): void
    {
        http_response_code($code);
    }

    /**
     * Přesměrování
     * @param string $url adresa požadované stránky
     * @return void
     */
    #[NoReturn] public function redirect(string $url): void
    {
        header("Location: $url");
        exit();
    }
}