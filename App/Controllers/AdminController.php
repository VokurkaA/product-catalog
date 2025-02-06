<?php

namespace App\Controllers;

class AdminController implements BaseController
{
    public function handle()
    {
        return require './App/Views/AdminView.php';
    }
}
