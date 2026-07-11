<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Computer extends Model
{
    protected $fillable = [
        'asset_id',
        'cpu',
        'ram',
        'ssd',
        'hdd',
        'gpu',
        'os',
        'office'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
