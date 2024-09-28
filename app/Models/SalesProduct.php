<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesProduct extends Model
{
    // use HasFactory;

    public $timestamps = false;
    protected $fillable = ['id_product', 'name_product', 'quantity_saled', 'price_product'];
    protected $primaryKey = 'id_sale';
    protected $table = 'tbl_salesproduct';
}