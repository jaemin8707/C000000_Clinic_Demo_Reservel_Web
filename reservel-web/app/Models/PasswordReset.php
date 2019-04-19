<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_resets';
    protected $guarded = ['id', 'created_at'];
    protected $dates = ['created_at'];
}
