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
            $newCart = array_filter($user->cart, fn($id) => $id !== $productId);
            $user->cart = array_values($newCart);
            Cache::set('user', $user);
            header('Location: /product-catalog/cart');
            exit();
        }

        if (isset($_POST['increment']) && isset($_POST['product_id'])) {
            $productId = (int)$_POST['product_id'];
            $newCart = $user->cart;
            $newCart[] = $productId;
            $user->cart = $newCart;
            Cache::set('user', $user);
            header('Location: /product-catalog/cart');
            exit();
        }

        if (isset($_POST['decrement']) && isset($_POST['product_id'])) {
            $productId = (int)$_POST['product_id'];
            $index = array_search($productId, $user->cart, true);
            if ($index !== false) {
                $newCart = $user->cart;
                unset($newCart[$index]);
                $user->cart = array_values($newCart);
            }
            Cache::set('user', $user);
            header('Location: /product-catalog/cart');
            exit();
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

        return require './App/Views/CartView.php';
    }
}
