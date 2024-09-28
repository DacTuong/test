<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;
    protected $fillable = ['product_code', 'product_name', 'product_image', 'product_price', 'product_quantity', 'categories_product', 'brand_product', 'product_status'];
    protected $primaryKey = 'product_id';
    protected $table = 'tbl_product';

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_product', 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_product', 'brand_id');
    }
    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'id_sanpham', 'product_id');
    }
}