<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ClientOrder extends Model
{
    protected $table = 'client_order';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $fillable = [
        'Order_Status_Id',
        'Client_Id',
        'Employee_Id',
        'Carrier_Id',
        'Client_Address',
        'Payment_Method_Id',
    ];
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'Order_Status_Id', 'Id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'Client_Id', 'Id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Employee_Id', 'Id');
    }
    public function carrier()
    {
        return $this->belongsTo(Employee::class, 'Carrier_Id', 'Id');
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'Payment_Method_Id', 'Id');
    }
}