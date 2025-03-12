<?php

namespace App\Controllers;

use App\Models\Cache;
use App\Models\Category;
use App\Models\User;

class ProductController implements BaseController
{
    public function handle()
    {
        $parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $productId = isset($parts[2]) ? (int)$parts[2] : null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
            return $this->handleAddToCart($productId);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
            return $this->handleLike($productId);
        }

        if ($productId === null) {
            header('Location: /product-catalog');
            exit();
        }

        $product = Cache::get('products')[$productId] ?? null;
        $categories = Cache::get('categories') ?? null;

        if ($product === null) {
            header('Location: /product-catalog');
            exit();
        }

        $subCategoryIds = Category::getParentIdsRecursively($product->categoryId, $categories);
        $user = Cache::get('user');
        $isLiked = $user && in_array($productId, $user->liked);
        return require_once './App/Views/ProductView.php';
    }

    private function handleAddToCart($productId)
    {
        $user = Cache::get('user');
        if (!$user || !($user instanceof User)) {
            header('Location: /product-catalog/login');
            exit();
        }
        $cart = $user->cart;
        array_push($cart, $productId);
        $user->cart = $cart;
        Cache::set('user', $user);
        header('Location: /product-catalog/product/' . $productId);
        exit();
    }

    private function handleLike($productId)
    {
        $user = Cache::get('user');
        if (!$user || !($user instanceof User)) {
            header('Location: /product-catalog/login');
            exit();
        }

        $liked = $user->liked;
        if (in_array($productId, $liked)) {
            $liked = array_filter($liked, function ($id) use ($productId) {
                return $id !== $productId;
            });
        } else {
            array_push($liked, $productId);
        }
        $user->liked = $liked;
        Cache::set('user', $user);
        header('Location: /product-catalog/product/' . $productId);
        exit();
    }
}
