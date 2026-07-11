<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'employee_id',
        'asset_id',
        'priority',
        'status'
    ];

    public static function generateTicketNumber()
    {
        $prefix = 'TIK-' . date('ymd') . '-';
        $lastTicket = self::where('ticket_number', 'like', $prefix . '%')
                          ->orderBy('id', 'desc')
                          ->first();

        if (!$lastTicket) {
            return $prefix . '001';
        }

        $lastNumber = intval(substr($lastTicket->ticket_number, -3));
        return $prefix . sprintf('%03d', $lastNumber + 1);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
