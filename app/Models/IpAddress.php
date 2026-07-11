<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IpAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ip_address',
        'mac_address',
        'asset_id',
        'employee_id',
        'vlan_id',
        'gateway',
        'dns',
        'status',
        'notes'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function vlan()
    {
        return $this->belongsTo(Vlan::class);
    }
}
