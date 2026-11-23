<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OrderStatus extends Model
{
    protected $table = 'order_status';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $fillable = ['Name'];
    public function orders()
    {
        return $this->hasMany(ClientOrder::class, 'Order_Status_Id', 'Id');
    }
}
?>