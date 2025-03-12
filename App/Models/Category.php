<?php

namespace App\Models;

class Category
{
    private int $id;
    private string $name;
    private ?int $parentId;
    private array $childrenIds;

    public function __construct($id, $name, $parentId = null, $childrenIds = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->parentId = $parentId;
        $this->childrenIds = $childrenIds;
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
    public static function getSubcategoriesRecursively($categoryId, $categories): array
    {
        if (!$categoryId) {
            return [];
        }
        $subcategories = [$categoryId];
        foreach ($categories[$categoryId]->childrenIds as $c) {
            $subcategories = array_merge($subcategories, self::getSubcategoriesRecursively($c, $categories));
        }
        return $subcategories;
    }
    public static function getParentIdsRecursively($categoryId, $categories): array
    {
        if (!$categoryId) {
            return [];
        }
        $ids = [$categoryId];
        return array_merge($ids, self::getParentIdsRecursively($categories[$categoryId]->parentId, $categories));
    }
}
