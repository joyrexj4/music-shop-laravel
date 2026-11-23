<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductBrand;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with(['brand', 'category'])
            ->where('IsDeleted', false)
            ->findOrFail($id);
        return view('product', compact('product'));
    }

    public function byCategory($id)
    {
        $category = ProductCategory::findOrFail($id);
        $products = Product::where('Category_Id', $id)->get();
        $categories = ProductCategory::orderBy('Name')->get();
        $brands = ProductBrand::orderBy('Name')->get();
        
        return view('search', compact('products', 'categories', 'brands', 'category'));
    }
}
?>