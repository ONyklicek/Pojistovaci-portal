<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Core;

use Tracy\Debugger;

final class Debuger
{
    public function __construct()
    {
        if (Bootstrap::$config['DevMode']['TracyBar'] === true)
            self::runDebug();
        else
            ini_set('display_errors', '0');
            ini_set('display_startup_errors', '0');
        if (Bootstrap::$config['DevMode']['ServerData'] === true)
            bdump($_SERVER);
    }
    public static function runDebug() : void
    {
        Debugger::enable(Debugger::DEVELOPMENT);
        $logger = Debugger::getLogger();
        Debugger::$logSeverity = E_WARNING | E_NOTICE;
        Debugger::dispatch();
        // Connect PhpStorm
        Debugger::$editorMapping = [
            // original => new
            '/var/www/html' => 'Users/ondrejnyklicek/Documents/Projects/www'
        ];
        Debugger::$editor = 'phpstorm://open?file=%file&line=%line';
    }
}

