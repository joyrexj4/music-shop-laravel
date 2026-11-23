<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::orderBy('Name')->get();
        $products = Product::with(['brand', 'category'])
            ->where('IsDeleted', false)
            ->orderBy('Id', 'desc')
            ->limit(12)
            ->get();

        return view('index', compact('categories', 'products'));
    }
}

?>