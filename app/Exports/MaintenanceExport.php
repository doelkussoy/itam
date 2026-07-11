<?php

namespace App\Exports;

use App\Models\Maintenance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MaintenanceExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Maintenance::with('asset')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Asset',
            'Description',
            'Maintenance Date',
            'Cost',
            'Status',
        ];
    }

    public function map($maintenance): array
    {
        return [
            $maintenance->id,
            $maintenance->asset ? $maintenance->asset->name : '-',
            $maintenance->description,
            $maintenance->maintenance_date,
            $maintenance->cost,
            $maintenance->status,
        ];
    }
}
