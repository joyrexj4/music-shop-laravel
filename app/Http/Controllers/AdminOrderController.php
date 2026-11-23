<?php
namespace App\Http\Controllers;
use App\Models\ClientOrder;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\Employee;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = ClientOrder::with(['orderStatus', 'client', 'employee', 'carrier', 'paymentMethod'])
            ->orderBy('Id', 'desc')
            ->paginate(15);
        
        return view('admin.orders.index', compact('orders'));
    }
    public function edit($id)
    {
        $order = ClientOrder::with(['orderStatus', 'client', 'employee', 'carrier', 'paymentMethod'])
            ->findOrFail($id);
        
        $orderStatuses = OrderStatus::all();
        $employees = Employee::with('job')->get();

        $carriers = Employee::with('job')
        ->whereHas('job', function($query) {
            $query->where('Name', 'like', '%Доставщик%');
        })
        ->get();
        
        $paymentMethods = PaymentMethod::all();
        
        return view('admin.orders.edit', compact('order', 'orderStatuses', 'employees', 'carriers', 'paymentMethods'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'order_status_id' => 'required|exists:order_status,Id',
            'employee_id' => 'nullable|exists:employee,Id',
            'carrier_id' => 'nullable|exists:employee,Id',
            'payment_method_id' => 'required|exists:payment_method,Id',
            'client_address' => 'required|string|max:100',
        ]);

        $order = ClientOrder::findOrFail($id);
        
        $order->Order_Status_Id = $request->order_status_id;
        $order->Employee_Id = $request->employee_id ?? Auth::guard('employee')->id();
        $order->Carrier_Id = $request->carrier_id;
        $order->Payment_Method_Id = $request->payment_method_id;
        $order->Client_Address = $request->client_address;
        $order->save();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Заказ успешно обновлен!');
    }
    public function show($id)
    {
        $order = ClientOrder::with(['orderStatus', 'client', 'employee', 'carrier', 'paymentMethod'])
            ->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'order_status_id' => 'required|exists:order_status,Id',
        ]);

        $order = ClientOrder::findOrFail($id);
        $order->Order_Status_Id = $request->order_status_id;
        $order->save();

        return response()->json(['success' => true]);
    }
    public function assignEmployee(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employee,Id',
        ]);

        $order = ClientOrder::findOrFail($id);
        $order->Employee_Id = $request->employee_id;
        $order->save();

        return response()->json(['success' => true]);
    }
    public function assignCarrier(Request $request, $id)
    {
        $request->validate([
            'carrier_id' => 'required|exists:employee,Id',
        ]);

        $order = ClientOrder::findOrFail($id);
        $order->Carrier_Id = $request->carrier_id;
        $order->save();

        return response()->json(['success' => true]);
    }
    public function search(Request $request)
    {
        $search = $request->get('search');
        
        $orders = ClientOrder::with(['orderStatus', 'client', 'employee', 'carrier', 'paymentMethod'])
            ->whereHas('client', function($query) use ($search) {
                $query->where('Surname', 'like', "%{$search}%")
                      ->orWhere('Name', 'like', "%{$search}%")
                      ->orWhere('Email', 'like', "%{$search}%");
            })
            ->orWhere('Id', 'like', "%{$search}%")
            ->orWhere('Client_Address', 'like', "%{$search}%")
            ->orderBy('Id', 'desc')
            ->paginate(15);
        
        return view('admin.orders.index', compact('orders'));
    }
}