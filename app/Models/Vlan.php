<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vlan_number',
        'name',
        'subnet',
        'gateway',
        'status',
        'notes'
    ];

    public function ipAddresses()
    {
        return $this->hasMany(IpAddress::class);
    }
}
