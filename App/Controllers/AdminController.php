<?php

namespace App\Controllers;

use App\Models\Cache;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class AdminController implements BaseController
{
    private $errors = [];

    public function handle()
    {
        try {
            $user = Cache::get('user');
            if (!$user || ($user->role !== 'admin' && $user->role !== 'owner')) {
                header('Location: /product-catalog');
                exit();
            }

            $products = Cache::get('products');
            $categories = Cache::get('categories');
            $users = Cache::get('users');

            $activeTab = $_GET['tab'] ?? 'products';
            $action = $_GET['action'] ?? '';
            $id = isset($_GET['id']) ? $_GET['id'] : null;

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                try {
                    if (isset($_POST['delete_product']) && isset($_POST['product_id'])) {
                        try {
                            unset($products[$_POST['product_id']]);
                            Cache::remove('products', $_POST['product_id']);
                        } catch (\Exception $e) {
                            $this->errors[] = "Failed to delete product: " . $e->getMessage();
                        }
                    } else if (isset($_POST['delete_category']) && isset($_POST['category_id'])) {
                        try {
                            unset($categories[$_POST['category_id']]);
                            Cache::remove('categories', $_POST['category_id']);
                        } catch (\Exception $e) {
                            $this->errors[] = "Failed to delete category: " . $e->getMessage();
                        }
                    } else if (isset($_POST['delete_user']) && isset($_POST['user_id'])) {
                        try {
                            unset($users[$_POST['user_id']]);
                            Cache::remove('users', $_POST['user_id']);
                        } catch (\Exception $e) {
                            $this->errors[] = "Failed to delete user: " . $e->getMessage();
                        }
                    } else if (isset($_POST['save_product'])) {
                        try {
                            $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : count($products) + 1;
                            $rating = isset($_POST['rating']) ? explode(',', $_POST['rating']) : [5];

                            if (empty($_POST['name'])) {
                                $this->errors[] = "Product name is required";
                            }
                            if (empty($_POST['brand'])) {
                                $this->errors[] = "Brand is required";
                            }
                            if (!is_numeric($_POST['price']) || $_POST['price'] < 0) {
                                $this->errors[] = "Price must be a valid positive number";
                            }
                            if (!is_numeric($_POST['stock']) || $_POST['stock'] < 0) {
                                $this->errors[] = "Stock must be a valid positive number";
                            }
                            if (!isset($categories[$_POST['category_id']])) {
                                $this->errors[] = "Selected category does not exist";
                            }

                            if (empty($this->errors)) {
                                $products[$productId] = new Product(
                                    $productId,
                                    htmlspecialchars($_POST['name']),
                                    htmlspecialchars($_POST['description']),
                                    htmlspecialchars($_POST['brand']),
                                    $_POST['price'],
                                    (int)$_POST['category_id'],
                                    array_map('intval', $rating),
                                    (int)$_POST['stock']
                                );
                                Cache::set('products', $products);
                                header('Location: /product-catalog/admin?tab=' . $activeTab);
                                exit();
                            }
                        } catch (\Exception $e) {
                            $this->errors[] = "Failed to save product: " . $e->getMessage();
                        }
                    } else if (isset($_POST['save_category'])) {
                        try {
                            $categoryId = isset($_POST['category_id']) ? (int)$_POST['category_id'] : count($categories) + 1;
                            $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

                            if (empty($_POST['name'])) {
                                $this->errors[] = "Category name is required";
                            }
                            if ($parentId !== null && !isset($categories[$parentId])) {
                                $this->errors[] = "Selected parent category does not exist";
                            }
                            if ($parentId === $categoryId) {
                                $this->errors[] = "Category cannot be its own parent";
                            }

                            if (empty($this->errors)) {
                                $categories[$categoryId] = new Category(
                                    $categoryId,
                                    htmlspecialchars($_POST['name']),
                                    $parentId
                                );

                                if ($parentId !== null) {
                                    $children = $categories[$parentId]->childrenIds;
                                    if (!in_array($categoryId, $children)) {
                                        $children[] = $categoryId;
                                        $categories[$parentId]->childrenIds = $children;
                                    }
                                }

                                Cache::set('categories', $categories);
                                header('Location: /product-catalog/admin?tab=' . $activeTab);
                                exit();
                            }
                        } catch (\Exception $e) {
                            $this->errors[] = "Failed to save category: " . $e->getMessage();
                        }
                    } else if (isset($_POST['save_user'])) {
                        try {
                            $username = htmlspecialchars(trim($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8');
                            $password = $_POST['password'] ?? '';
                            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                            $role = $_POST['role'] ?? 'user';
                            $phoneNumber = $_POST['phone_number'] ?? null;
                            $address = $_POST['address'] ?? null;

                            if (empty($username)) {
                                $this->errors[] = "Username is required";
                            }
                            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $this->errors[] = "Valid email is required";
                            }
                            if (empty($password)) {
                                $this->errors[] = "Password is required";
                            }
                            if (empty($role)) {
                                $this->errors[] = "Role is required";
                            }
                            if (empty($this->errors)) {
                                $users = Cache::get('users');
                                if ($action == 'edit' && isset($id)) {
                                    $userId = $users[$id]->id;
                                    $users[$id] = new User(
                                        $userId,
                                        $username,
                                        password_hash($password, PASSWORD_BCRYPT),
                                        $email,
                                        $role,
                                        $phoneNumber,
                                        $address
                                    );
                                } else {
                                    $users[] = new User(
                                        null,
                                        $username,
                                        password_hash($password, PASSWORD_BCRYPT),
                                        $email,
                                        $role,
                                        $phoneNumber,
                                        $address
                                    );
                                }
                                Cache::set('users', $users);
                                header('Location: /product-catalog/admin?tab=' . $activeTab);
                                exit();
                            }
                        } catch (\Exception $e) {
                            $this->errors[] = "Failed to save user: " . $e->getMessage();
                        }
                    }
                } catch (\Exception $e) {
                    $this->errors[] = "An error occurred processing the request: " . $e->getMessage();
                }

                if (!empty($this->errors)) {
                    header('Location: /product-catalog/admin?tab=' . $activeTab);
                    exit();
                }
            }
            return require './App/Views/AdminView.php';
        } catch (\Exception $e) {
            $this->errors[] = "Critical error: " . $e->getMessage();
            return require './App/Views/AdminView.php';
        }
    }
}
