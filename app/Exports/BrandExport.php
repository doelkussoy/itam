<?php

namespace App\Exports;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BrandExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Brand::all();
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

    public function map($brand): array
    {
        return [
            $brand->id,
            $brand->name,
            $brand->description,
            $brand->created_at,
            $brand->updated_at,
        ];
    }
}
