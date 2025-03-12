<?php

namespace App\Controllers;

use App\Models\Cache;
use App\Models\Category;
use App\Models\Product;

class HomeController implements BaseController
{
    public function handle()
    {
        $products = Cache::get('products');
        $categories = Cache::get('categories');

        $currentPage = max(1, intval($_GET['page'] ?? 1));
        $itemsPerPage = 3 * 9;

        $selectedCategories = Category::getSubcategoriesRecursively((int)($_GET['category'] ?? null), $categories);
        $idHierarchy = Category::getParentIdsRecursively((int)($_GET['category'] ?? null), $categories);

        $selectedProducts = Product::filterProductsBySearch($_GET['search'] ?? '', $products);
        $selectedProducts = Product::filterProductsByCategory($selectedCategories, $selectedProducts);
        $selectedProducts = Product::sortProducts(($_GET['sort'] ?? null), $selectedProducts);

        $totalProducts = count($selectedProducts);
        $totalPages = ceil($totalProducts / $itemsPerPage);
        $currentPage = min($currentPage, $totalPages);
        $offset = ($currentPage - 1) * $itemsPerPage;
        $selectedProducts = array_slice($selectedProducts, $offset, $itemsPerPage);

        return require_once './App/Views/HomeView.php';
    }
}
