<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkDetail extends Model
{
    protected $table = 'network_details';

    protected $fillable = [
        'asset_id',
        'firmware',
        'port_count',
        'active_ports',
        'backup_config_path',
        'ssid',
        'wifi_password',
        'controller'
    ];

    protected $casts = [
        'wifi_password' => 'encrypted'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
