<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = false;
    protected $fillable = ['name_user', 'email_user', 'password_user', 'status_user', 'phone_user'];
    protected $primaryKey = 'id_user';
    protected $table = 'tbl_user';
}
