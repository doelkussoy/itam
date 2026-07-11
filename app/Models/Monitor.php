<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    protected $fillable = [
        'asset_id',
        'size'
    ];

    protected $casts = [
        'size' => 'decimal:1'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
