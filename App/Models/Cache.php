<?php

namespace App\Models;

use App\Models\Database;

class Cache
{
    private static $isInit = false;
    private static $memoryCache = [
        'user' => null,
        'products' => null,
        'categories' => null
    ];

    private static function init()
    {
        if (self::$isInit) {
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['cache'])) {
            $_SESSION['cache'] = [
                'user' => null,
                'products' => null,
                'categories' => null
            ];
        }

        if (!self::$memoryCache['products']) {
            $products = self::initProducts();
            self::$memoryCache['products'] = $products;
            $_SESSION['cache']['products'] = $products;
        }

        if (!self::$memoryCache['categories']) {
            $categories = self::initCategories();
            self::$memoryCache['categories'] = $categories;
            $_SESSION['cache']['categories'] = $categories;
        }

        self::$isInit = true;
    }

    public static function get($key)
    {
        if (is_array($key)) {
            self::initUser($key['username'], $key['password']);
            return self::get('user');
        }
        if (!in_array($key, ['products', 'categories', 'user', 'users'])) {
            throw new \InvalidArgumentException("Invalid cache key: {$key}");
        }

        if (!self::$isInit) {
            self::init();
        }

        if (isset(self::$memoryCache[$key])) {
            return self::$memoryCache[$key];
        }

        if (isset($_SESSION['cache'][$key])) {
            self::$memoryCache[$key] = $_SESSION['cache'][$key];
            return $_SESSION['cache'][$key];
        }

        switch ($key) {
            case 'products':
                $value = self::initProducts();
                break;

            case 'categories':
                $value = self::initCategories();
                break;

            case 'users':
                $data = Database::query('SELECT * FROM users');
                $value = [];
                foreach ($data as $userData) {
                    $cart = array_map('intval', array_filter(explode(',', trim($userData['cart'], '{}'))));
                    $liked = array_map('intval', array_filter(explode(',', trim($userData['liked'], '{}'))));
                    $previousPurchases = array_map('intval', array_filter(explode(',', trim($userData['previous_purchases'], '{}'))));
                    $value[$userData['id']] = new User(
                        $userData['id'],
                        $userData['username'],
                        $userData['password_hash'],
                        $userData['email'],
                        $userData['role'] ?? 'user',
                        $userData['phone_number'],
                        $userData['address'],
                        $cart,
                        $liked,
                        $previousPurchases
                    );
                }
                break;

            default:
                $value = null;
        }

        if ($value !== null) {
            self::set($key, $value);
        }

        return $value;
    }

    public static function set($key, $value)
    {
        if (!in_array($key, ['user', 'products', 'categories', 'users'])) {
            throw new \InvalidArgumentException("Invalid cache key: {$key}");
        }

        if (!self::$isInit) {
            self::init();
        }

        self::$memoryCache[$key] = $value;
        $_SESSION['cache'][$key] = $value;

        if ($key == 'user') {
            self::syncUserToDatabase($value);
        }
        if ($key == 'users') {
            foreach ($value as $user) {
                self::syncUserToDatabase($user);
            }
        }

        if ($key == 'products') {
            foreach ($value as $product) {
                self::syncProductToDatabase($product);
            }
        }
        if ($key == 'categories') {
            foreach ($value as $category) {
                self::syncCategoryToDatabase($category);
            }
        }
    }

    public static function clear($key = null)
    {
        if ($key !== null) {
            if (!in_array($key, ['user', 'products', 'categories', 'users'])) {
                throw new \InvalidArgumentException("Invalid cache key: {$key}");
            }
            self::$memoryCache[$key] = null;
            if (isset($_SESSION['cache'])) {
                $_SESSION['cache'][$key] = null;
            }
        } else {
            self::$memoryCache = [
                'user' => null,
                'products' => null,
                'categories' => null
            ];
            if (isset($_SESSION['cache'])) {
                $_SESSION['cache'] = self::$memoryCache;
            }
            self::$isInit = false;
        }
    }

    private static function initProducts()
    {
        $products = Database::query('SELECT * FROM products');
        $result = [];
        foreach ($products as $p) {
            $result[$p['id']] = new Product($p['id'], $p['name'], $p['description'], $p['brand'], $p['price'], $p['category_id'], array_map('intval', explode(',', $p['rating'])), $p['stock']);
        }
        return $result;
    }

    private static function initCategories()
    {
        $categories = Database::query('SELECT * FROM categories');
        $result = [];
        foreach ($categories as $c) {
            $result[$c['id']] = new Category($c['id'], $c['name'], $c['parent_id'] ?? null);
        }

        foreach ($result as $category) {
            if ($category->parentId !== null && isset($result[$category->parentId])) {
                $currentChildren = $result[$category->parentId]->childrenIds;
                $currentChildren[] = $category->id;
                $result[$category->parentId]->childrenIds = $currentChildren;
            }
        }
        return $result;
    }

    public static function initUser($username, $password)
    {
        $userdata = Database::query("SELECT * FROM users WHERE username = :username", [':username' => $username]);
        if (!empty($userdata) && password_verify($password, $userdata[0]['password_hash'])) {
            $cart = array_map('intval', array_filter(explode(',', trim($userdata[0]['cart'], '{}'))));
            $liked = array_map('intval', array_filter(explode(',', trim($userdata[0]['liked'], '{}'))));
            $previousPurchases = array_map('intval', array_filter(explode(',', trim($userdata[0]['previous_purchases'], '{}'))));
            $user = new User(
                $userdata[0]['id'],
                $userdata[0]['username'],
                $userdata[0]['password_hash'],
                $userdata[0]['email'],
                $userdata[0]['role'] ?? 'user',
                $userdata[0]['phone_number'],
                $userdata[0]['address'],
                $cart,
                $liked,
                $previousPurchases,
            );
            self::set('user', $user);
        }
    }

    private static function syncUserToDatabase($user)
    {
        if (!$user) {
            throw new \InvalidArgumentException("Invalid user object");
        }

        $exists = $user->id != null;

        if ($exists) {
            $params = [
                ':id' => $user->id,
                ':username' => $user->username,
                ':password_hash' => $user->passwordHash,
                ':email' => $user->email,
                ':role' => $user->role,
                ':phone_number' => $user->phoneNumber,
                ':address' => $user->address,
                ':cart' => '{' . implode(',', $user->cart) . '}',
                ':liked' => '{' . implode(',', $user->liked) . '}',
                ':previous_purchases' => '{' . implode(',', $user->previousPurchases) . '}'
            ];

            $query = "UPDATE users SET username = :username, password_hash = :password_hash, 
                     email = :email, role = :role, phone_number = :phone_number, 
                     address = :address, cart = :cart, liked = :liked, 
                     previous_purchases = :previous_purchases 
                     WHERE id = :id";
        } else {
            $params = [
                ':username' => $user->username,
                ':password_hash' => $user->passwordHash,
                ':email' => $user->email,
                ':role' => $user->role,
                ':phone_number' => $user->phoneNumber,
                ':address' => $user->address,
                ':cart' => '{' . implode(',', $user->cart) . '}',
                ':liked' => '{' . implode(',', $user->liked) . '}',
                ':previous_purchases' => '{' . implode(',', $user->previousPurchases) . '}'
            ];

            $query = "INSERT INTO users (username, password_hash, email, role, phone_number, 
                     address, cart, liked, previous_purchases) 
                     VALUES (:username, :password_hash, :email, :role, :phone_number, 
                     :address, :cart, :liked, :previous_purchases)";
        }

        $result = Database::query($query, $params);

        if (!$exists && $result) {
            $userData = Database::query(
                "SELECT id FROM users WHERE username = :username AND email = :email",
                [':username' => $user->username, ':email' => $user->email]
            );
            if (!empty($userData)) {
                $user->id = $userData[0]['id'];
            }
        }
    }

    private static function syncProductToDatabase($product)
    {
        if (!$product) {
            throw new \InvalidArgumentException("Invalid product object");
        }

        $params = [
            ':id' => $product->id,
            ':name' => $product->name,
            ':description' => $product->description,
            ':brand' => $product->brand,
            ':price' => $product->price,
            ':category_id' => $product->categoryId,
            ':rating' => '{' . implode(',', $product->rating) . '}',
            ':stock' => $product->stock
        ];

        $exists = Database::query("SELECT id FROM products WHERE id = :id", [':id' => $product->id]);

        $query = $exists ?
            "UPDATE products SET name = :name, description = :description, brand = :brand, 
             price = :price, category_id = :category_id, rating = :rating, stock = :stock 
             WHERE id = :id" :
            "INSERT INTO products (id, name, description, brand, price, category_id, rating, stock) 
             VALUES (:id, :name, :description, :brand, :price, :category_id, :rating, :stock)";

        Database::query($query, $params);
    }
    private static function syncCategoryToDatabase($category)
    {
        if (!$category) {
            throw new \InvalidArgumentException("Invalid category object");
        }

        $params = [
            ':id' => $category->id,
            ':name' => $category->name,
            ':parent_id' => $category->parentId
        ];

        $exists = Database::query("SELECT id FROM categories WHERE id = :id", [':id' => $category->id]);

        $query = $exists ?
            "UPDATE categories SET name = :name, parent_id = :parent_id WHERE id = :id" :
            "INSERT INTO categories (id, name, parent_id) VALUES (:id, :name, :parent_id)";

        Database::query($query, $params);
    }
}
