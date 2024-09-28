<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    public $timestamps = false;
    protected $fillable = ['name_coupon', 'coupon_code', 'coupon_qty', 'coupon_type', 'discount', 'customer_id', 'start_date', 'end_date'];
    protected $primaryKey = 'id_coupon';
    protected $table = 'tbl_coupons';
}