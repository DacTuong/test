<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['contact_id', 'order_code', 'message', 'read'];
    protected $primaryKey = 'id_notice';
    protected $table = 'tbl_notice'; // Adjust if your table name is different


}
