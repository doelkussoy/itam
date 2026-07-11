<?php

namespace App\Exports;

use App\Models\IpAddress;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IpAddressExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return IpAddress::with(['asset', 'location'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'IP Address',
            'Subnet Mask',
            'Gateway',
            'Asset',
            'Location',
            'Status',
            'Description',
        ];
    }

    public function map($ip): array
    {
        return [
            $ip->id,
            $ip->ip_address,
            $ip->subnet_mask,
            $ip->gateway,
            $ip->asset ? $ip->asset->name : '-',
            $ip->location ? $ip->location->name : '-',
            $ip->status,
            $ip->description,
        ];
    }
}
