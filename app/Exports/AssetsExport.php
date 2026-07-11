<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AssetsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Asset::with(['category', 'brand', 'location'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Asset Code',
            'Name',
            'Serial Number',
            'Category',
            'Brand',
            'Location',
            'Date Received',
            'Delivery Order Number',
            'Warranty (Months)',
            'Status',
            'Notes',
            'Created At'
        ];
    }

    public function map($asset): array
    {
        return [
            $asset->id,
            $asset->asset_tag,
            $asset->name,
            $asset->serial_number,
            $asset->category ? $asset->category->name : '',
            $asset->brand ? $asset->brand->name : '',
            $asset->location ? $asset->location->name : '',
            $asset->date_received,
            $asset->delivery_order_number,
            $asset->warranty_months,
            $asset->status,
            $asset->notes,
            $asset->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
