<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    // use HasFactory;
    public $timestamps = false;
    protected $fillable = ['order_code', 'product_id', 'product_name', 'product_price', 'product_sale_quantity'];
    protected $primaryKey = 'id_order_detail';
    protected $table = 'tbl_order_details';

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_id', 'id_shipping');
    }
}
