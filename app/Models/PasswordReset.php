<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PasswordReset extends Model
{
    use SoftDeletes;

    protected $table = 'password_resets';

    protected $fillable = [
        'email',
        'otp_code',
        'otp_time',
        'has_verified'
    ];
}
