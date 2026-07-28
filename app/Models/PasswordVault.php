<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class PasswordVault extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'device_name',
        'username',
        'encrypted_password',
        'category',
        'notes',
    ];

    protected $casts = [];

    protected function encryptedPassword(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (empty($value)) {
                    return null;
                }
                try {
                    return Crypt::decryptString($value);
                } catch (DecryptException $e) {
                    return '[Decryption Failed - Invalid Key]';
                }
            },
            set: fn ($value) => Crypt::encryptString($value),
        );
    }
}
