<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    protected $fillable = ['first_name', 'email', 'mobile', 'password', 'status'];

   
}
