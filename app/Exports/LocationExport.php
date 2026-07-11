<?php

namespace App\Exports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LocationExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Location::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Description',
            'Created At',
            'Updated At',
        ];
    }

    public function map($location): array
    {
        return [
            $location->id,
            $location->name,
            $location->description,
            $location->created_at,
            $location->updated_at,
        ];
    }
}
