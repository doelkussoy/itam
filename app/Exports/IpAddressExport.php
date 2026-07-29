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
        return IpAddress::with(['asset', 'employee', 'vlan'])->orderByRaw('INET_ATON(ip_address)')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'IP Address',
            'MAC Address',
            'Assigned Asset',
            'User / Employee',
            'VLAN',
            'Gateway',
            'DNS',
            'Status',
            'Ping Status',
            'Notes',
        ];
    }

    public function map($ip): array
    {
        return [
            $ip->id,
            $ip->ip_address,
            $ip->mac_address ?: '-',
            $ip->asset ? $ip->asset->name : '-',
            $ip->employee ? $ip->employee->name : '-',
            $ip->vlan ? 'VLAN ' . $ip->vlan->vlan_number : '-',
            $ip->gateway ?: '-',
            $ip->dns ?: '-',
            $ip->status,
            $ip->is_online === true ? 'Online' : ($ip->is_online === false ? 'Offline' : 'Unchecked'),
            $ip->notes ?: '-',
        ];
    }
}
