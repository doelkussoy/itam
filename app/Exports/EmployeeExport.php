<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employee::with(['department', 'position'])->get();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama',
            'Email',
            'Telepon',
            'Departemen',
            'Jabatan',
            'Status'
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->employee_id,
            $employee->name,
            $employee->email,
            $employee->phone,
            $employee->department ? $employee->department->name : '-',
            $employee->position ? $employee->position->name : '-',
            $employee->status
        ];
    }
}
