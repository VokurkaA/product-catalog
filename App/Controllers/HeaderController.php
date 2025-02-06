<?php

namespace App\Controllers;

use App\Models\Cache;

class HeaderController implements BaseController
{
    public function handle()
    {
        $user = Cache::get('user');
        return require_once './App/Views/HeaderView.php';
    }
}