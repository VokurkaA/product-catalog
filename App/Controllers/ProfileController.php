<?php

namespace App\Controllers;

use App\Models\Cache;
use App\Models\User;

class ProfileController implements BaseController
{
    private $errors = [];

    public function handle()
    {
        $user = Cache::get('user');
        if (!$user || !($user instanceof User)) {
            header('Location: /product-catalog/login');
            exit();
        }

        // Handle logout request
        if (isset($_POST['logout'])) {
            Cache::set('user', null);
            header('Location: /product-catalog/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
            $username = htmlspecialchars(trim($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8');
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $phoneNumber = htmlspecialchars(trim($_POST['phoneNumber'] ?? ''), ENT_QUOTES, 'UTF-8');
            $address = htmlspecialchars(trim($_POST['address'] ?? ''), ENT_QUOTES, 'UTF-8');

            if (empty($username)) {
                $this->errors[] = "Username is required";
            } elseif (strlen($username) < 1) {
                $this->errors[] = "Please enter a valid username";
            }

            if (empty($email)) {
                $this->errors[] = "Email is required";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "Invalid email format";
            }

            if (!empty($phoneNumber) && !preg_match('/^[0-9+\-\s()]*$/', $phoneNumber)) {
                $this->errors[] = "Invalid phone number format";
            }

            if (empty($this->errors)) {
                try {
                    Cache::set('user',$user);
                } catch (\Exception $e) {
                    $this->errors[] = "Failed to update profile. Please try again.";
                }
            }
        }

        $errors = $this->errors;
        $products = Cache::get('products');
        return require './App/Views/ProfileView.php';
    }
}
