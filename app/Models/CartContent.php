<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartContent extends Model
{
    protected $table = 'cart_content';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Cart_Id',
        'Product_Id',
        'Amount',
        'Price',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'Cart_Id', 'Id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_Id', 'Id');
    }
}
?>