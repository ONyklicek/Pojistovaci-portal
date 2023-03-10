<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Model;

use App\Core\Database\DbModel;
use App\Core\Request;

class ProductModel extends DbModel
{
    /**
     * Výpis všech produktů
     * @return array|bool
     */
    public function getProducts(): array|bool
    {
        return self::selectAll("SELECT product_id, product_name, product_active FROM products ORDER BY product_id");
    }

    /**
     * Výpis produktu
     * @param int $id
     * @return array|bool
     */
    public function getProduct(int $id): array|bool
    {
        return self::selectOne("SELECT product_id, product_name, product_active 
                                    FROM products
                                    WHERE product_id = ? "
            , array($id));
    }

    /**
     * Přidání pojištění
     * @param array $product
     * @return void
     * @throws \Exception
     */
    public function addProduct(array $product): void
    {
        if (empty($product['product_name'])) {
            throw new \Exception('Název pojištění nesmí být prázdný.');
        }

        try {
            self::insert('products', $product);
        } catch (\PDOException $error){
            throw new \Exception('Při zpracování nastala chyba: ' . $error->getMessage());
        }
    }

    /**
     * Aktualizace produktu
     * @param int $id
     * @param array $product
     * @return void
     * @throws \Exception
     */
    public function updateProduct(int $id, array $product): void
    {
        if(empty($product['product_name'])) {
            throw new \Exception('Název pojištění nesmí být prázdný.');
        }

        $dbKey = array('product_name', 'product_desc', 'product_active');
        $productData = array_intersect_key($product, array_flip($dbKey));
        try {
            self::update('products', $productData, 'WHERE product_id = ?', array($id));
        }
        catch (\PDOException $error){
                throw new \Exception('Při zpracování nastala chyba: ' . $error->getMessage());
            }
    }

    /**
     * Odstranění produktu
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function deleteProduct(int $id): void
    {
        try {
            self::query('DELETE FROM products WHERE product_id = ? ', array($id));
        } catch (\PDOException $error) {
            throw new \Exception('Odstranění pojistění se nezdařilo');
        }
    }
}