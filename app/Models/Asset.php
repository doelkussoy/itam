<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    /** @use HasFactory<\Database\Factories\AssetFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_tag', 'name', 'serial_number', 'category_id', 'brand_id',
        'location_id', 'date_received', 'delivery_order_number',
        'warranty_months', 'status', 'notes', 'spec_data'
    ];

    protected $casts = [
        'spec_data' => 'array',
        'date_received' => 'date',
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function brand() { return $this->belongsTo(Brand::class); }
    public function location() { return $this->belongsTo(Location::class); }

    public function computer() { return $this->hasOne(Computer::class); }
    public function printer() { return $this->hasOne(Printer::class); }
    public function monitor() { return $this->hasOne(Monitor::class); }
    public function networkDetail() { return $this->hasOne(NetworkDetail::class); }
    public function cctv() { return $this->hasOne(Cctv::class); }

    public function assignments() { return $this->hasMany(AssetAssignment::class); }
    public function currentAssignment() { return $this->hasOne(AssetAssignment::class)->where('status', 'Assigned')->latestOfMany(); }
    public function maintenances() { return $this->hasMany(Maintenance::class); }
    public function ipAddresses() { return $this->hasMany(IpAddress::class); }
}
