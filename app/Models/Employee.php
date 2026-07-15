<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id', 'name', 'email', 'phone', 'department_id', 
        'status', 'supervisor_id', 'location_id', 'anydesk_id', 'anydesk_password',
        'login_username', 'login_password'
    ];

    protected function loginPassword(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function ($value) {
                if (empty($value)) return null;
                try {
                    return \Illuminate\Support\Facades\Crypt::decryptString($value);
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    return '[Decryption Failed]';
                }
            },
            set: fn ($value) => empty($value) ? null : \Illuminate\Support\Facades\Crypt::encryptString($value),
        );
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'supervisor_id');
    }

    public function assignments()
    {
        return $this->hasMany(AssetAssignment::class);
    }

    public function ipAddresses()
    {
        return $this->hasMany(IpAddress::class);
    }

    public function softwareLicenses()
    {
        return $this->hasMany(SoftwareLicense::class, 'pic_id');
    }
}
