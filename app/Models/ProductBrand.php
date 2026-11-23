<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProductBrand extends Model
{
    protected $table = 'product_brand';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $fillable = ['Name'];
    public function products()
    {
        return $this->hasMany(Product::class, 'Brand_Id', 'Id');
    }
}
?>