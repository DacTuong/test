<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{

    public $timestamps = false;
    protected $fillable = ['name', 'type', 'matp'];
    protected $primaryKey = 'maqh';
    protected $table = 'devvn_quanhuyen'; // Adjust if your table name is different

    public function province()
    {
        return $this->belongsTo(Province::class, 'matp', 'matp');
    }

    public function wards()
    {
        return $this->hasMany(Ward::class, 'maqh', 'maqh');
    }
}
