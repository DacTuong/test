<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    public $timestamps = false;
    protected $fillable = ['id_sanpham	', 'gallery_path'];
    protected $primaryKey = 'id_gallery';
    protected $table = 'tbl_gallery';

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_sanpham', 'product_id');
    }
}