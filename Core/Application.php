<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */
namespace App\Core;


class Application
{
    public static Application $app;
    private static string $rootDir;
    public string $layout = 'main';

    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Controller|null $controller = null;
    public View $view;

    public function __construct(string $rootPath)
    {
        self::setRootDir($rootPath);
        self::$app = $this;
        self::setRequest(new Request());
        self::setResponse(new Response());
        self::setSession(new Session());
        self::setRouter(new Router(self::getRequest(), self::getResponse()));
        self::setView(new View());
    }

    /**
     * Získání požadavku
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Získání Routru
     *
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * Získání kodu odpovědi
     *
     * @return Response
     */
    public function getResponse() : Response
    {
        return $this->response;
    }

    /**
     * Získání controleru
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * Získání RootDir
     *
     * @return string
     */
    public static function getRootDir(): string
    {
        return self::$rootDir;
    }

    /**
     * Nastavení požaadavku
     *
     * @param Request $request
     */
    private function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * Nastavení routru
     *
     * @param Router $router
     */
    private function setRouter(Router $router): void
    {
        $this->router = $router;
    }

    /**
     * Nastevení kódu odpovědi
     *
     * @param Response $response
     */

    private function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    /**
     * Nastavení kořenového adresáře
     *
     * @param string $rootDir
     */
    private static function setRootDir(string $rootDir): void
    {
        self::$rootDir = $rootDir;
    }


    /** Nastavení kontroleru
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * Nastavení sessionu
     * @param Session $session
     * @return void
     */
    protected function setSession(Session $session): void
    {
        $this->session = $session;
    }

    /**
     * Nastavení pohledu
     * @param View $view
     */
    public function setView(View $view): void
    {
        $this->view = $view;
    }


    /**
     * Ověření oprávnění - Admin
     * @return bool
     */
    public static function isAdmin(): bool
    {
        if((isset($_SESSION['user'])) && ($_SESSION['user']['user_type'] === 0)){
            return true;
        } else {
            return false;
        }
    }


    /**
     * Kontrola přihlášení
     * @return bool
     */
    public function isGuest(): bool
    {
        if (empty($_SESSION['user'])) {
            return true;
        }
        return false;
    }

    /**
     * Spuštění aplikace
     *
     * @return void
     * @throws \Exception
     */
    public function run() : void
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            throw new \Exception(''. $e->getMessage());
        }

    }
}