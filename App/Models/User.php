<?php

namespace App\Models;

class User
{
    private string $id;
    private string $username;
    private string $passwordHash;
    private string $email;
    private string $role;
    private ?string $phoneNumber;
    private ?string $address;
    private array $cart = [];
    private array $liked = [];
    private array $previousPurchases = [];

    public function __construct($id, $username, $passwordHash, $email, $role = 'user', $phoneNumber = null, $address = null, $cart = [], $liked = [], $previousPurchases = [])
    {
        $this->id = $id;
        $this->username = $username;
        $this->passwordHash = $passwordHash;
        $this->email = $email;
        $this->role = $role;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
        $this->cart = $cart;
        $this->liked = $liked;
        $this->previousPurchases = $previousPurchases;
    }
    function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new \Exception("Property {$name} does not exist");
    }
    function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            throw new \Exception("Property {$name} does not exist");
        }
    }
}
