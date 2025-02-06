<?php

namespace App\Controllers;

use App\Models\Cache;
use App\Models\User;

class CartController implements BaseController
{
    public function handle()
    {
        $user = Cache::get('user');
        if (!$user || !($user instanceof User)) {
            header('Location: /product-catalog/login');
            exit();
        }

        if (isset($_POST['remove']) && isset($_POST['product_id'])) {
            $productId = (int)$_POST['product_id'];
            $user->cart = array_values(array_filter($user->cart, function ($id) use ($productId) {
                return $id !== $productId;
            }));
            Cache::set('user', $user);
            header('Location: /product-catalog/cart');
            exit();
        }

        $products = Cache::get('products');
        $cartProductsIds = $user->cart;
        // Group products by ID and calculate quantities
        $groupedProducts = [];
        foreach ($cartProductsIds as $id) {
            if (!isset($groupedProducts[$id])) {
                $groupedProducts[$id] = [
                    'product' => $products[$id],
                    'quantity' => 1
                ];
            } else {
                $groupedProducts[$id]['quantity']++;
            }
        }

        return require './App/Views/CartView.php';
    }
}
