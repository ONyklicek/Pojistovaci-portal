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
use Fig\Http\Message\StatusCodeInterface;

class Router implements StatusCodeInterface, RequestMethodInterface
{

    public Request $request;
    public Response $response;
    protected array $routeMap = [];

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        self::setRequest($request);
        self::setResponse($response);
    }

    /**
     * Zíkání požadavku
     *
     * @param $url
     * @param $callback - Volaná funkce
     * @return void
     */
    public function get($url, $callback) : void
    {
        self::setRouteMap(self::METHOD_GET, $url, $callback);
    }

    /**
     * Odeslání požadavku
     *
     * @param $url
     * @param $callback
     * @return void
     */
    public function post($url, $callback) : void
    {
        self::setRouteMap(self::METHOD_POST, $url, $callback);
    }

    /**
     * Zpracování požadavku
     *
     * @return string|void|null
     */
    public function resolve()
    {
        $method = self::getRequest()->getMethod();
        $url = self::getRequest()->getUrl();
        $callback = self::getRoute($method, $url);

        if (!$callback){
            $callback = self::getCallbeck();

            if($callback === false) {
                Application::$app->response->statusCode(self::STATUS_NOT_FOUND);
                return self::renderView('_error');
            }
        }

        //Vrácení stringu
        if (is_string($callback)){
            return self::renderView($callback);
        }

        //Vrácení instance volané třídy
        if (is_array($callback)) {
            Application::$app->setController(new $callback[0]);  //vytvoření instance, je-li volána třída
            Application::$app->controller->setAction($callback[1]);
            $callback[0] = Application::$app->getController();
        }

        return call_user_func($callback, $this->request, $this->response);

    }

    /**
     *  Získání parametrů z URL
     *
     * @return false|mixed
     */
    public function getCallbeck(): mixed
    {
        $method = self::getRequest()->getMethod();
        $url = self::getRequest()->getUrl();
        $routes = $this->getRouteMap($method);

        foreach ($routes as $route => $callback) {
            $patternAsRegex = self::getRegex($route);

            preg_match($patternAsRegex, $url, $matches);

            $params = array_intersect_key(
                $matches,
                array_flip(array_filter(array_keys($matches), 'is_string'))
            );
            if (!empty($params)) {
                $this->request->setRouteParams($params);
                return $callback;
            }
        }

        return false;

    }
    private function getRegex($pattern)
    {
        if (preg_match('/[^-:\/_{}()a-zA-Z\d]/', $pattern))
            return false; // Invalid pattern

        // Turn "(/)" into "/?"
        $pattern = preg_replace('#\(/\)#', '/?', $pattern);

        // Create capture group for ":parameter"
        $allowedParamChars = '[a-zA-Z0-9\_\-]+';
        $pattern = preg_replace(
        '/:(' . $allowedParamChars . ')/',   # Replace ":parameter"
        '(?<$1>' . $allowedParamChars . ')', # with "(?<parameter>[a-zA-Z0-9\_\-]+)"
        $pattern
        );

        // Create capture group for '{parameter}'
        $pattern = preg_replace(
        '/{('. $allowedParamChars .')}/',    # Replace "{parameter}"
        '(?<$1>' . $allowedParamChars . ')', # with "(?<parameter>[a-zA-Z0-9\_\-]+)"
        $pattern
        );

        // Add start and end matching
        $patternAsRegex = "@^" . $pattern . "$@D";

        return $patternAsRegex;
    }

    /**
     * Volání pohledu
     *
     * @param string $view
     * @param array $data
     * @return array|string|string[]
     */
    public function renderView(string $view, array $head = [], ?array $data = []) : array|string
    {
        return Application::$app->view->renderView($view, $head, $data);
    }

    /**
     * @return array
     */
    public function getRoute(string $method, string $url): array|false
    {
        return $this->routeMap[$method][$url] ?? false;
    }

    public function getRouteMap(string $method): array
    {
        return $this->routeMap[$method];
    }

    /**
     * @param array $routeMap
     */
    private function setRouteMap(string $method, string $url, array $routeMap): void
    {
        $this->routeMap[$method][$url] = $routeMap;
    }

    /**
     * Získání obsahu a zabezpečení šablony
     *
     * @param string $view
     * @param array|string $data
     * @return false|string|null
     */
    protected function renderViewOnly(string $view, array|string $data): String|False|Null
    {
        return Application::$app->view->renderViewOnly($view, $data);
    }

    /**
     * Získání požadavku URL
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Nastevení kódu odpovědi
     *
     * @param Response $response
     */
    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    /**
     * Nastavení požadavku URL
     *
     * @param Request $request
     */
    private function setRequest(Request $request) : void
    {
        $this->request = $request;
    }

}