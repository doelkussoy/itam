<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Ticket::with(['asset', 'employee'])->get();
    }

    public function headings(): array
    {
        return [
            'Ticket ID',
            'Title',
            'Description',
            'Status',
            'Priority',
            'Asset',
            'User',
            'Created At',
        ];
    }

    public function map($ticket): array
    {
        return [
            $ticket->ticket_number ?? $ticket->id,
            $ticket->title,
            $ticket->description,
            $ticket->status,
            $ticket->priority,
            $ticket->asset ? $ticket->asset->name : '-',
            $ticket->employee ? $ticket->employee->name : '-',
            $ticket->created_at,
        ];
    }
}
