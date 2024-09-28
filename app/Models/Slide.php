<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    // use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name_slide', 'slide_image', 'status_user'];
    protected $primaryKey = 'id_slide';
    protected $table = 'tbl_slide';
}