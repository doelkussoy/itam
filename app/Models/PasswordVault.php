<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordVault extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'device_name',
        'username',
        'encrypted_password',
        'category',
        'notes'
    ];

    protected $casts = [
        'encrypted_password' => 'encrypted'
    ];
}
