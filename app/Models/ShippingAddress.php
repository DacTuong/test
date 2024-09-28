<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    public $timestamps = false;
    protected $fillable = ['id_customer', 'fullname', 'order_phone', 'matp', 'maqh', 'xaid'];
    protected $primaryKey = 'id_shipping';
    protected $table = 'tbl_shipping_address';

    public function orders()
    {
        return $this->hasMany(OrderProduct::class, 'shipping_id', 'id_shipping');
    }

    public function districts()
    {
        return $this->belongsTo(District::class, 'matp', 'matp');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'matp', 'matp');
    }

    public function wards()
    {
        return $this->belongsTo(Ward::class, 'maqh', 'maqh');
    }
}
