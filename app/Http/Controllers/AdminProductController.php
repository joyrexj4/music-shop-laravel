<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['brand', 'category'])->active()->orderBy('Id', 'desc')->paginate(10);
        return view('admin.products.index', compact('products'));
    }
    public function trashed()
    {
        $products = Product::with(['brand', 'category'])
            ->deleted()
            ->orderBy('Id', 'desc')
            ->paginate(10);
        return view('admin.products.trashed', compact('products'));
    }
    public function create()
    {
        $brands = ProductBrand::orderBy('Name')->get();
        $categories = ProductCategory::orderBy('Name')->get();
        return view('admin.products.create', compact('brands', 'categories'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'details' => 'nullable|string|max:200',
            'description' => 'nullable|string|max:500',
            'article' => 'required|integer|unique:product,Article',
            'price' => 'required|numeric|min:0',
            'brand_id' => 'required|exists:product_brand,Id',
            'category_id' => 'required|exists:product_category,Id',
            'amount' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = ProductCategory::find($request->category_id);
        $categoryName = strtolower($category->Name);

        $product = new Product();
        $product->Name = $request->name;
        $product->Details = $request->details;
        $product->Description = $request->description;
        $product->Article = $request->article;
        $product->Price = $request->price;
        $product->Brand_Id = $request->brand_id;
        $product->Category_Id = $request->category_id;
        $product->Amount = $request->amount;
        $product->IsDeleted = false;

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $destinationPath = public_path("images/products/{$categoryName}");
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $imageName = strtolower(str_replace(' ', '_', $request->name)) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);

            $product->Image_Path = "images/products/{$categoryName}/{$imageName}";
        } else {
            $product->Image_Path = 'images/default-product.jpg';
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Товар добавлен');
    }
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $brands = ProductBrand::orderBy('Name')->get();
        $categories = ProductCategory::orderBy('Name')->get();
        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'details' => 'nullable|string|max:200',
            'description' => 'nullable|string|max:500',
            'article' => 'required|integer|unique:product,Article,' . $id . ',Id',
            'price' => 'required|numeric|min:0',
            'brand_id' => 'required|exists:product_brand,Id',
            'category_id' => 'required|exists:product_category,Id',
            'amount' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $oldCategory = ProductCategory::find($product->Category_Id);
        $oldCategoryName = strtolower($oldCategory->Name);
        
        $newCategory = ProductCategory::find($request->category_id);
        $newCategoryName = strtolower($newCategory->Name);

        $product->Name = $request->name;
        $product->Details = $request->details;
        $product->Description = $request->description;
        $product->Article = $request->article;
        $product->Price = $request->price;
        $product->Brand_Id = $request->brand_id;
        $product->Category_Id = $request->category_id;
        $product->Amount = $request->amount;

        if ($request->hasFile('image')) {
            if ($product->Image_Path && $product->Image_Path != "images/default-product.jpg") {
                $oldImagePath = public_path($product->Image_Path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $image = $request->file('image');
            
            $destinationPath = public_path("images/products/{$newCategoryName}");
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $imageName = strtolower(str_replace(' ', '_', $request->name)) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
            $product->Image_Path = "images/products/{$newCategoryName}/{$imageName}";
            
        } elseif ($product->Category_Id != $request->category_id && $product->Image_Path && $product->Image_Path != "images/default-product.jpg") {
            $oldImagePath = public_path($product->Image_Path);
            $oldFileName = basename($oldImagePath);
            
            $newDirectory = public_path("images/products/{$newCategoryName}");
            if (!file_exists($newDirectory)) {
                mkdir($newDirectory, 0755, true);
            }
            
            $newImagePath = "images/products/{$newCategoryName}/{$oldFileName}";
            
            if (file_exists($oldImagePath)) {
                rename($oldImagePath, public_path($newImagePath));
                $product->Image_Path = $newImagePath;
            }
            
            $oldDirectory = public_path("images/products/{$oldCategoryName}");
            if (file_exists($oldDirectory) && count(scandir($oldDirectory)) == 2) {
                rmdir($oldDirectory);
            }
        }
        $product->save();
        return redirect()->route('admin.products.index')->with('success', 'Товар обновлен');
    }
    public function forceDelete($id)
    {
        $product = Product::findOrFail($id);
    
        if ($product->Image_Path && $product->Image_Path != "images/default-product.jpg") {
            $imagePath = public_path($product->Image_Path);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            $category = ProductCategory::find($product->Category_Id);
            if ($category) {
                $categoryName = strtolower($category->Name);
                $categoryDir = public_path("images/products/{$categoryName}");
                if (file_exists($categoryDir) && count(scandir($categoryDir)) == 2) {
                    rmdir($categoryDir);
                }
            }
        }        
        $product->delete();
        return redirect()->route('admin.products.trashed')->with('success', 'Товар окончательно удален!');
    }
    public function restore($id)
    {
        $product = Product::findOrFail($id);
        $product->IsDeleted = false;
        $product->save();

        return redirect()->route('admin.products.trashed')
            ->with('success', 'Товар восстановлен!');
    }  
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
    
        $product->IsDeleted = true;
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Товар перемещен в список удалённых');
    }
    public function search(Request $request)
    {
        $search = $request->get('search');
        $products = Product::with(['brand', 'category'])
            ->where('Name', 'like', "%{$search}%")
            ->orWhere('Article', 'like', "%{$search}%")
            ->orderBy('Id', 'desc')
            ->paginate(10);
        
        return view('admin.products.index', compact('products'));
    }
}