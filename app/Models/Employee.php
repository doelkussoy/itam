<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id', 'name', 'email', 'phone', 'department_id',
        'status', 'supervisor_id', 'location_id', 'anydesk_id', 'anydesk_password',
        'login_username', 'login_password',
    ];

    protected function loginPassword(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (empty($value)) {
                    return null;
                }
                try {
                    return Crypt::decryptString($value);
                } catch (DecryptException $e) {
                    return '[Decryption Failed]';
                }
            },
            set: fn ($value) => empty($value) ? null : Crypt::encryptString($value),
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
