<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoftwareLicense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'license_key',
        'expiry_date',
        'total_seats',
        'pic_id',
        'notes'
    ];

    protected $casts = [
        'expiry_date' => 'date'
    ];

    public function pic()
    {
        return $this->belongsTo(Employee::class, 'pic_id');
    }
}
