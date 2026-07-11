<?php

namespace App\Exports;

use App\Models\Position;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PositionExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Position::with('department')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Department',
            'Description',
            'Created At',
            'Updated At',
        ];
    }

    public function map($position): array
    {
        return [
            $position->id,
            $position->name,
            $position->department ? $position->department->name : '-',
            $position->description,
            $position->created_at,
            $position->updated_at,
        ];
    }
}
