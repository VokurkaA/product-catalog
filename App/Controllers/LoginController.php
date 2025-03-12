<?php

namespace App\Controllers;

use App\Models\Cache;

class LoginController implements BaseController
{
    private $errors = [];

    public function handle()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleLogin();
        }
        return $this->displayForm();
    }

    private function handleLogin()
    {
        $username = htmlspecialchars(trim($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8');
        $password = $_POST['password'] ?? '';

        if (empty($username)) {
            $this->errors[] = "Username is required";
        }
        if (empty($password)) {
            $this->errors[] = "Password is required";
        }

        if (empty($this->errors)) {
            try {
                Cache::clear('user');
                Cache::get(['username' => $username, 'password' => $password]);
                header('Location: /product-catalog/profile');
            } catch (\Exception $e) {
                $this->errors[] = "Login failed. Please try again.";
            }
        }

        return $this->displayForm();
    }

    private function displayForm()
    {
        $errors = $this->errors;
        return require './App/Views/LoginView.php';
    }
}
