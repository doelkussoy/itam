<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cctv extends Model
{
    protected $table = 'cctvs';

    protected $fillable = [
        'asset_id',
        'nvr_channel',
        'firmware',
        'username',
        'password'
    ];

    protected $casts = [
        'password' => 'encrypted'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
