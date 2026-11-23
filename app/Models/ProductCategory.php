<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProductCategory extends Model
{
    protected $table = 'product_category';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $fillable = ['Name'];
    public function products()
    {
        return $this->hasMany(Product::class, 'Category_Id', 'Id');
    }
}
?>