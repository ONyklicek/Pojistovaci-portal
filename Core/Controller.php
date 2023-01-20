<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Core;

class Controller
{
    protected string $layout = 'main';
    public string|null $action;

    /**
     * Vrátí pohled (voláno z Controlleru)
     *
     * @param string $view
     * @param array $data
     * @return array|string|string[]
     */
    public function render(string $view, array $head = [], ?array $data = [])
    {
        return Application::$app->router->renderView($view, $head, $data);
    }

    /**
     * Nastavení layout
     *
     * @return string
     */
    public function getLayout(): string
    {
        return $this->layout;
    }

    /**
     * Záskání layout
     *
     * @param string $layout
     */
    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    /**
     * Získaní action (funkce volaná routrem)
     *
     * @return string|null
     */
    public function getAction(): string|null
    {
        return $this->action;
    }

    /**
     * Nastavení action (funkce volaná routrem)
     *
     * @param string|null $action
     */
    public function setAction(string|null $action): void
    {
        $this->action = $action;
    }
}