<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Controller;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Request;
use App\Model\ProductModel;

class ProductController extends Controller
{
    /**
     * Výpis všech produktů
     * @return array|string
     */
    public function products(): array|string
    {
        self::isLogged();
        if(!Application::isAdmin()){
            Application::$app->response->redirect('/404');
        }

        $head = [
            'title' => "Produkty"
        ];
        $userModel = new ProductModel();
        $data = $userModel->getProducts();


        return self::render(__FUNCTION__, $head,  $data);
    }

    /**
     * Editace produktu
     * @param Request $request
     * @return array|string
     */
    public function editProduct(Request $request): array|string
    {
        self::isLogged();
        if(!Application::isAdmin()){
            Application::$app->response->redirect('/404');
        }

        $requestId = $request->getRouteParam('id');

        if ($request->isPost()){
            $productModel = new ProductModel();
            $formData = $request->getBody();

            try {
                if(isset($requestId)) {
                    $head = [
                        'title' => 'Editace produktu',
                    ];
                    $productModel->updateProduct($requestId, $formData);
                    Application::$app->session->setFlash('success', 'Pojištění bylo úspěšně aktualizováno');
                } else {
                    $head = [
                        'title' => 'Přidání nového produktu',
                    ];
                    $productModel->addProduct($formData);
                    Application::$app->session->setFlash('success', 'Pojistění bylo úspěšně přidáno');
                }
                Application::$app->response->redirect("/products");
            } catch (\Exception $e){
                Application::$app->session->setFlash('warning', 'Pojištění s tímto názvem existuje');
                return self::render(__FUNCTION__, $head, $formData);
            }
        }

        if (isset($requestId)) {
            $head = [
                'title' => 'Editace produktu',
            ];

            $productModel = new ProductModel();
            $data = $productModel->getProduct($requestId);

            return self::render(__FUNCTION__, $head, $data);
        }

        $head = [
            'title' => 'Přidání nového produktu',
        ];
        return self::render(__FUNCTION__, $head, []);
    }

    /**
     * Odstranění produktu
     *
     * @param Request $request
     * @return void
     */
    public function deleteProduct(Request $request): void
    {
        if(!Application::isAdmin()){
            Application::$app->response->redirect('/404');
        }
        self::isLogged();

        $requestId = $request->getRouteParam('id');
        $productModel = new ProductModel();

        try {
            $productModel->deleteProduct($requestId);
            Application::$app->session->setFlash('success', 'Pojištění bylo úspěšně odstraněno.');
            Application::$app->response->redirect('/products');
        } catch (\Exception $e){
            Application::$app->session->setFlash('warning', $e->getMessage());
            Application::$app->response->redirect('/products');
        }

    }

}