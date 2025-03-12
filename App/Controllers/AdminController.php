<?php

namespace App\Controllers;

use App\Models\Cache;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class AdminController implements BaseController
{
    private $errors = [];

    public function handle()
    {
        try {
            $user = Cache::get('user');
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            $user = null;
        }

        if (!$user || ($user->role !== 'admin' && $user->role !== 'owner')) {
            header('Location: /product-catalog');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if (method_exists($this, $action) && is_callable([$this, $action])) {
                return $this->$action();
            }
        }

        try {
            $products = Cache::get('products') ?? [];
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            $products = [];
        }

        try {
            $categories = Cache::get('categories') ?? [];
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            $categories = [];
        }

        try {
            $users = Cache::get('users') ?? [];
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            $users = [];
        }

        $selectedProductId = $_POST['selectedProductId'] ?? null;
        $selectedCategoryId = $_POST['selectedCategoryId'] ?? null;
        $selectedUserId = $_POST['selectedUserId'] ?? null;

        $selectedProduct = $selectedProductId ? $products[$selectedProductId] : null;

        return require './App/Views/AdminView.php';
    }

    private function addProduct()
    {
        try {
            $products = Cache::get('products') ?? [];
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            $products = [];
        }

        $newProduct = new Product(
            count($products),
            $_POST['name'],
            $_POST['description'],
            $_POST['brand'],
            $_POST['price'],
            $_POST['stock'],
            $_POST['category_id'],
            []
        );
        $products[] = $newProduct;

        try {
            Cache::set('products', $products);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        header('Location: /product-catalog/admin');
        exit();
    }

    private function updateProduct()
    {
        try {
            $products = Cache::get('products');
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            $products = [];
        }

        $id = (int)$_POST['product_id'];
        $products[$id]->name = $_POST['name'];
        $products[$id]->description = $_POST['description'];
        $products[$id]->brand = $_POST['brand'];
        $products[$id]->price = (float)$_POST['price'];
        $products[$id]->stock = (int)$_POST['stock'];
        $products[$id]->categoryId = (int)$_POST['category_id'];

        try {
            Cache::set('products', $products);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        header('Location: /product-catalog/admin');
        exit();
    }

    private function removeProduct()
    {
        try {
            $products = Cache::get('products');
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            $products = [];
        }

        $id = (int)$_POST['product_id'];
        unset($products[$id]);

        try {
            Cache::set('products', $products);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        header('Location: /product-catalog/admin');
        exit();
    }

    private function addCategory()
    {
        try {
            $categories = Cache::get('categories') ?? [];
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            $categories = [];
        }

        $newCategory = new Category(
            count($categories),
            $_POST['name'],
            !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null
        );
        $categories[] = $newCategory;

        try {
            Cache::set('categories', $categories);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        header('Location: /product-catalog/admin');
        exit();
    }

    private function updateCategory()
    {
        try {
            $categories = Cache::get('categories');
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            $categories = [];
        }

        $id = (int)$_POST['selectedCategoryId'];
        $categories[$id]->name = $_POST['name'];
        $categories[$id]->parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

        try {
            Cache::set('categories', $categories);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        header('Location: /product-catalog/admin');
        exit();
    }

    private function removeCategory()
    {
        try {
            $categories = Cache::get('categories');
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            $categories = [];
        }

        $id = (int)$_POST['selectedCategoryId'];
        unset($categories[$id]);

        try {
            Cache::set('categories', $categories);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        header('Location: /product-catalog/admin');
        exit();
    }

    private function updateUser()
    {
        try {
            $users = Cache::get('users');
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            $users = [];
        }

        $id = (int)$_POST['selectedUserId'];
        $users[$id]->username = $_POST['username'];
        $users[$id]->email = $_POST['email'];
        if (!empty($_POST['newPassword'])) {
            $users[$id]->password = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);
        }
        $users[$id]->phoneNumber = $_POST['phoneNumber'];
        $users[$id]->role = $_POST['role'];
        $users[$id]->address = $_POST['address'];

        try {
            Cache::set('users', $users);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        header('Location: /product-catalog/admin');
        exit();
    }

    private function removeUser()
    {
        try {
            $users = Cache::get('users');
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            $users = [];
        }

        $id = (int)$_POST['selectedUserId'];
        unset($users[$id]);

        try {
            Cache::set('users', $users);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        header('Location: /product-catalog/admin');
        exit();
    }

    private function addUser()
    {
        try {
            $users = Cache::get('users') ?? [];
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            $users = [];
        }

        $newUser = new User(
            count($users),
            $_POST['username'],
            $_POST['email'],
            password_hash($_POST['password'], PASSWORD_BCRYPT),
            $_POST['phoneNumber'],
            $_POST['role'],
            $_POST['address']
        );
        $users[] = $newUser;

        try {
            Cache::set('users', $users);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        header('Location: /product-catalog/admin');
        exit();
    }
}
