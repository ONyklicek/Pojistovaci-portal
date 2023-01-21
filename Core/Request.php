<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Core;

use Fig\Http\Message\RequestMethodInterface;

class Request implements RequestMethodInterface
{
    private array $routeParams;
    /**
     * Získání zadaní URL
     *
     * @return mixed|string
     */
    public function getUrl()
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    /**
     * Získání metody požadavku
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Ověření metody GETe
     *
     * @return bool
     */
     public function isGet(): bool
    {
        return $this->getMethod() === self::METHOD_GET;
    }

    /**
     * Ověření metody POST
     *
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->getMethod() === self::METHOD_POST;
    }

    /**
     * Získání id uživatele
     * @return int|null
     */
    public function getUserId(): int|null
    {
        if (isset($_SESSION['user']['user_id']))
            return $_SESSION['user']['user_id'];
        else
            return false;
    }

    /**
     * Kontrola přihlášení
     * @return bool
     */
    public function isLogged(): bool
    {
        if (isset($_SESSION['user'])) {
            return true;
        }
        return false;
    }


    /**
     * Získání těla
     * @return array
     */
    public function getBody(): array
    {
        foreach ($_POST as $key => $value) {
            $data[$key] = ($value);
        }

        if (empty($data)) return [];
        return $data;

    }

    /**
     * @param array $params
     * @return $this
     */
    public function setRouteParams(array $params): static
    {
        $this->routeParams = $params;
        return $this;
    }

    /**
     * @param $param
     * @param $default
     * @return mixed|null
     */
    public function getRouteParam($param, $default = null): mixed
    {
        return $this->routeParams[$param] ?? $default;
    }

    /**
     * @return array
     */
    public function getRouteParams(): array
    {
        return $this->routeParams;

    }
}