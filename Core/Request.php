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
     * @return mixed
     */
    public function getUserId(): int|null
    {
        return $_SESSION['user']['user_id'];
    }

    /**
     * Získání těla
     *
     * @return array
     */
    public function getBody()
    {
        foreach ($_POST as $key => $value) {
            $data[$key] = ($value);
        }

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
     * @return string
     */
    public function getRouteParams(): array
    {
        return $this->routeParams;

    }
}