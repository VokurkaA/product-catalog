<?php

namespace App\Controllers;

use App\Models\Cache;

class CheckoutController implements BaseController
{
    public function handle()
    {
        $user = Cache::get('user');
        $errors = [];

        if (!$user) {
            header('Location: /product-catalog/login');
            exit();
        }

        if (empty($user->cart)) {
            header('Location: /product-catalog');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Cache::clear('products');
            $products = Cache::get('products');

            Cache::clear('categories');
            $categories = Cache::get('categories');

            $cartProducts = [];
            foreach ($user->cart as $productId) {
                if (!isset($cartProducts[$productId])) {
                    $cartProducts[$productId] = 1;
                } else {
                    $cartProducts[$productId]++;
                }
            }

            $validPurchase = true;
            foreach ($cartProducts as $productId => $quantity) {
                if (!isset($products[$productId])) {
                    $errors[] = 'Some products in your cart are no longer available';
                    $validPurchase = false;
                    break;
                }

                if (!isset($categories[$products[$productId]->categoryId])) {
                    $errors[] = 'Some products in your cart are no longer available';
                    $validPurchase = false;
                    break;
                }

                if ($products[$productId]->stock < $quantity) {
                    $errors[] = 'Not enough stock for ' . $products[$productId]->name;
                    $validPurchase = false;
                    break;
                }
            }

            if ($validPurchase) {
                foreach ($cartProducts as $productId => $quantity) {
                    $products[$productId]->stock -= $quantity;
                }
                Cache::set('products', $products);

                $user->previousPurchases = array_merge($user->previousPurchases, $user->cart);
                $user->cart = [];
                Cache::set('user', $user);

                header('Location: /product-catalog');
                exit();
            }
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
