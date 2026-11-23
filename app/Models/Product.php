<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $fillable = [
        'Name',
        'Details',
        'Description',
        'Article',
        'Image_Path',
        'Price',
        'Brand_Id',
        'Category_Id',
        'Amount',
        'IsDeleted',
    ];
    protected $casts = [
        'IsDeleted' => 'boolean',
    ];
    public function scopeActive($query)
    {
        return $query->where('IsDeleted', false);
    }
    public function scopeDeleted($query)
    {
        return $query->where('IsDeleted', true);
    }
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'Category_Id', 'Id');
    }
    public function brand()
    {
        return $this->belongsTo(ProductBrand::class, 'Brand_Id', 'Id');
    }
    public function getFormattedPriceAttribute()
    {
        return number_format($this->Price, 0, ',', ' ') . ' ₽';
    }
    public function softDelete()
    {
        $this->IsDeleted = true;
        return $this->save();
    }
    public function restore()
    {
        $this->IsDeleted = false;
        return $this->save();
    }
    public function isDeleted()
    {
        return $this->IsDeleted;
    }
}