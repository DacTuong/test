<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    // use HasFactory;
    public $timestamps = false;
    protected $fillable = ['order_code', 'order_email', 'shipping_id', 'id_customer', 'order_total', 'order_status', 'shipping_method'];
    protected $primaryKey = 'id_order';
    protected $table = 'tbl_order';

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_id', 'id_shipping');
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'order_code', 'order_code');
    }
}