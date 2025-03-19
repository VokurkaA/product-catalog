<?php

namespace App\Models;

class Product
{

    private int $id;
    private string $name;
    private string $description;
    private string $brand;
    private string $price;
    private int $categoryId;
    private array $rating;
    private int $stock;

    public function __construct($id, $name, $description, $brand, $price, $categoryId, $rating, $stock)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->brand = $brand;
        $this->price = $price;
        $this->categoryId = $categoryId;
        $this->rating = $rating;
        $this->stock = $stock;
    }

    public static function filterProductsByCategory($categoryIds, $products)
    {
        if (empty($categoryIds)) {
            return $products;
        }
        $result = [];
        foreach ($products as $p) {
            if (in_array($p->categoryId, $categoryIds)) {
                array_push($result, $p);
            }
        }
        return $result;
    }

    public static function filterProductsBySearch($name, $products)
    {
        if (empty($name)) {
            return $products;
        }
        $result = [];
        foreach ($products as $p) {
            if (stripos($p->name, $name) !== false || stripos($p->brand, $name) !== false) {
                array_push($result, $p);
            }
        }
        return $result;
    }

    public static function sortProducts($sort, $products)
    {
        if (!$sort) {
            return $products;
        }
        switch ($sort) {
            case 'default':
                return $products;
            case 'name_asc':
                usort($products, function ($a, $b) {
                    return $a->name <=> $b->name;
                });
                break;
            case 'name_desc':
                usort($products, function ($a, $b) {
                    return $b->name <=> $a->name;
                });
                break;
            case 'price_asc':
                usort($products, function ($a, $b) {
                    return $a->price <=> $b->price;
                });
                break;
            case 'price_desc':
                usort($products, function ($a, $b) {
                    return $b->price <=> $a->price;
                });
                break;
            case 'rating_desc':
                usort($products, function ($a, $b) {
                    return $b->rating[0] <=> $a->rating[0];
                });
                break;
        }
        return $products;
    }

    function __get($val)
    {
        if (property_exists($this, $val)) {
            return $this->$val;
        }
        throw new \Exception("Property {$val} does not exist");
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
