<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feeship extends Model
{
    public $timestamps = false;
    protected $fillable = ['matp', 'maqh', 'xaid', 'feeship'];
    protected $primaryKey = 'id_feeship';
    protected $table = 'tbl_feeship';
}
