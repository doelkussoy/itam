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

    protected $casts = [];

    protected function encryptedPassword(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function ($value) {
                if (empty($value)) return null;
                try {
                    return \Illuminate\Support\Facades\Crypt::decryptString($value);
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    return '[Decryption Failed - Invalid Key]';
                }
            },
            set: fn ($value) => \Illuminate\Support\Facades\Crypt::encryptString($value),
        );
    }
}
