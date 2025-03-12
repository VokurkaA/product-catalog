<?php

namespace App\Controllers;

use App\Models\Cache;

class CheckoutController implements BaseController
{
    public function handle()
    {
        $user = Cache::get('user');

        if (!$user) {
            header('Location: /product-catalog/login');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user->cart = [];
            Cache::set('user', $user);
            die('Thank you for your purchase!');
        }
        $products = Cache::get('products');
        $cartProductsIds = $user->cart;
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

        $total = 0;
        foreach ($groupedProducts as $groupedProduct) {
            $total += $groupedProduct['product']->price * $groupedProduct['quantity'];
        }
        return require_once './App/Views/CheckoutView.php';
    }    

}