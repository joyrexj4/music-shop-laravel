<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PaymentMethod extends Model
{
    protected $table = 'payment_method';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $fillable = ['Name'];
    public function orders()
    {
        return $this->hasMany(ClientOrder::class, 'Payment_Method_Id', 'Id');
    }
}
?>