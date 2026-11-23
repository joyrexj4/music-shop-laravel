<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
class SearchController extends Controller
{
    public function search(Request $request)
    {
        // Фильтры
        $query = Product::with(['brand', 'category'])
            ->where('IsDeleted', false);

        if ($request->filled('category') && $request->category != 0) {
            $query->where('Category_Id', $request->category);
        }
        
        if ($request->filled('brand') && $request->brand != 0) {
            $query->where('Brand_Id', $request->brand);
        }
        
        if ($request->filled('range_from')) {
            $query->where('Price', '>=', $request->range_from);
        }
        
        if ($request->filled('range_to')) {
            $query->where('Price', '<=', $request->range_to);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('Name', 'like', '%' . $request->search . '%')
                  ->orWhere('Article', 'like', '%' . $request->search . '%');
            });
        }    
        $products = $query->orderBy('Id', 'desc')->paginate(12);
        
        $categories = ProductCategory::orderBy('Name')->get();
        $brands = ProductBrand::orderBy('Name')->get();
        
        return view('search', compact('products', 'categories', 'brands'))->with([
            'selected_category' => $request->category ?? 0,
            'selected_brand' => $request->brand ?? 0,
            'price_from' => $request->range_from ?? '',
            'price_to' => $request->range_to ?? '',
        ]);
    }
}