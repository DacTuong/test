<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'type', 'maqh'];
    protected $primaryKey = 'xaid';
    protected $table = 'devvn_xaphuongthitran'; // Adjust if your table name is different
    public function district()
    {
        return $this->belongsTo(District::class, 'maqh', 'maqh');
    }
}
