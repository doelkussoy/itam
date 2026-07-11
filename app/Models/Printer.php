<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    protected $fillable = [
        'asset_id',
        'type',
        'connection_type',
        'has_scanner',
        'counter_print',
        'toner_status',
        'drum_status'
    ];

    protected $casts = [
        'has_scanner' => 'boolean'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
