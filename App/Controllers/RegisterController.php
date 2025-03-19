<?php

namespace App\Controllers;

use App\Models\Cache;
use App\Models\User;

class RegisterController implements BaseController
{
    private $errors = [];

    public function handle()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleRegistration();
        }
        return $this->displayForm();
    }

    private function handleRegistration()
    {
        // Validate input
        $username = htmlspecialchars(trim($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($username)) {
            $this->errors[] = "Username is required";
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Valid email is required";
        }
        if (strlen($password) < 4) {
            $this->errors[] = "Password must be at least 4 characters";
        }
        if ($password !== $confirmPassword) {
            $this->errors[] = "Passwords do not match";
        }

        if (empty($this->errors)) {
            try {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                if ($hashedPassword === false) {
                    throw new \Exception("Password hashing failed");
                }

                Cache::set('user', new User(null, $username, $hashedPassword, $email));
                header('Location: /product-catalog/profile');
                exit;
            } catch (\Exception $e) {
                die(var_dump($e));
                $this->errors[] = "Registration failed. Please try again.";
            }
        }

        return $this->displayForm();
    }

    private function displayForm()
    {
        $errors = $this->errors;
        return require './App/Views/RegisterView.php';
    }
}
