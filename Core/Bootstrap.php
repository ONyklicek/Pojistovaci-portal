<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Core;

use Symfony\Component\Yaml\Yaml;

final class Bootstrap
{
    public static array $config;

    public function __construct()
    {
        self::setAppConfig(__DIR__.'/../Config/app.yaml');
    }

    public function setAppConfig(?string $configFile): void
    {
        $yaml = Yaml::parse(file_get_contents($configFile));

        foreach ($yaml as $key => $value) {
            self::$config[$key] = $value;
        }
    }

}
