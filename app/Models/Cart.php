<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Client_Id',
        'Total',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'Client_Id', 'Id');
    }

    public function contents()
    {
        return $this->hasMany(CartContent::class, 'Cart_Id', 'Id');
    }
}
?>