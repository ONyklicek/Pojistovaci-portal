<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Core;

class View
{
    public function renderView(string $view, array $head, ?array $data): array|string
    {
        $layoutName = Application::$app->layout;
        if (Application::$app->controller) {
            $layoutName = Application::$app->controller->getLayout();
        }
        if(isset($_SESSION['user'])){
            foreach (self::secureView($_SESSION['user']) as $key => $value)
                $$key = $value;
        }
        //Secure View
        foreach (self::secureView($head) as $key => $value)
            $$key = $value;

        $viewContent = self::renderViewOnly($view, $head, $data);
        ob_start();
        include_once Application::getRootDir()."/views/layouts/$layoutName.phtml";
        $layoutContent = ob_get_clean();
        return str_replace('[{content}]', $viewContent, $layoutContent);

    }

    /**
     * Zabezpečení dat ve výstupu
     * @param mixed $data  vstupní data
     * @return mixed
     */
    private function secureView(mixed $data): mixed
    {
        if (!isset($data))
            return null;
        elseif (is_string($data))
            return htmlspecialchars($data, ENT_QUOTES);
        elseif (is_array($data))
        {
            foreach($data as $k => $v)
            {
                $data[$k] = $this->secureView($v);
            }
            return $data;
        }
        else {
            return $data;
        }
    }

    public function renderViewOnly(string $view, array $head, ?array $data): array|string
    {
        ob_start();

        foreach (self::secureView($head) as $key => $value)
            $$key = $value;

        if(isset($data)) {
            foreach (self::secureView($data) as $key => $value)
                $$key = $value;
        }

        include_once Application::getRootDir() . "/Views/$view.phtml";
        return ob_get_clean();
    }








}
