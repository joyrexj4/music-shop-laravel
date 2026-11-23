<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartContent;
use App\Models\ClientOrder;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class CartController extends Controller
{
    public function index()
    {
        $categories = \App\Models\ProductCategory::orderBy('Name')->get();
        $cart = $this->getOrCreateCart();
        $cartContents = $cart->contents()->with('product')->get();
        $total = $cart->Total ?? 0;
        
        return view('cart', compact('categories', 'cartContents', 'total'));
    }
    private function getOrCreateCart()
    {
        $clientId = Auth::guard('client')->id();
        
        if (!$clientId) {
            return null;
        }
        
        $cart = Cart::where('Client_Id', $clientId)->first();
        
        if (!$cart) {
            $cart = Cart::create([
                'Client_Id' => $clientId,
                'Total' => 0
            ]);
        }
        
        return $cart;
    }
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,Id',
            'quantity' => 'integer|min:1'
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;
        
        $product = Product::find($productId);
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        
        $cart = $this->getOrCreateCart();
        
        if (!$cart) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $cartContent = CartContent::where('Cart_Id', $cart->Id)
            ->where('Product_Id', $productId)
            ->first();
        
        if ($cartContent) {
            $cartContent->Amount += $quantity;
            $cartContent->Price = $product->Price * $cartContent->Amount;
            $cartContent->save();
        } else {
            CartContent::create([
                'Cart_Id' => $cart->Id,
                'Product_Id' => $productId,
                'Amount' => $quantity,
                'Price' => $product->Price * $quantity,
            ]);
        }

        $this->updateCartTotal($cart->Id);
        
        return response()->json([
            'success' => true,
            'message' => 'Товар добавлен в корзину'
        ]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,Id',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cart = $this->getOrCreateCart();
        
        if (!$cart) {
            return response()->json(['error' => 'Cart not found'], 404);
        }
        
        $cartContent = CartContent::where('Cart_Id', $cart->Id)
            ->where('Product_Id', $request->product_id)
            ->first();
        
        if ($cartContent) {
            $product = Product::find($request->product_id);
            $cartContent->Amount = $request->quantity;
            $cartContent->Price = $product->Price * $request->quantity;
            $cartContent->save();

            $this->updateCartTotal($cart->Id);
        }
        
        return response()->json(['success' => true]);
    }
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,Id'
        ]);
        
        $cart = $this->getOrCreateCart();
        
        if ($cart) {
            CartContent::where('Cart_Id', $cart->Id)
                ->where('Product_Id', $request->product_id)
                ->delete();
            
            $this->updateCartTotal($cart->Id);
        }
        
        return response()->json(['success' => true]);
    }
    private function updateCartTotal($cartId)
    {
        $total = CartContent::where('Cart_Id', $cartId)->sum('Price');
        
        Cart::where('Id', $cartId)->update(['Total' => $total]);
        
        if ($total == 0) {
            Cart::where('Id', $cartId)->delete();
        }
    }
    public function checkout(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|exists:payment_method,Id',
        ]);

        $cart = $this->getOrCreateCart();
        
        if (!$cart || $cart->Total <= 0) {
            return redirect()->route('cart')->with('error', 'Корзина пуста');
        }
        
        $cartContents = $cart->contents()->with('product')->get();
        
        if ($cartContents->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Корзина пуста');
        }
        
        DB::beginTransaction();
        
        try {
            $order = ClientOrder::create([
                'Order_Status_Id' => 1,
                'Client_Id' => Auth::guard('client')->id(),
                'Employee_Id' => null,
                'Carrier_Id' => null,
                'Client_Address' => $request->address,
                'Payment_Method_Id' => $request->payment_method,
            ]);
            CartContent::where('Cart_Id', $cart->Id)->delete();
            $cart->delete();
            
            DB::commit();        
            return redirect()->route('cart')->with('success', 'Заказ оформлен');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Произошла ошибка при оформлении заказа: ' . $e->getMessage());
        }
    }
}
?>