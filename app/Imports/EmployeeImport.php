<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Try to find department by name, or create if not exists (optional, keeping it simple to just find for now)
        $department = Department::where('name', 'like', $row['departemen'] ?? '')->first();
        $position = Position::where('name', 'like', $row['jabatan'] ?? '')->first();

        // If employee_id already exists, skip or update. Let's use updateOrCreate
        return Employee::updateOrCreate(
            ['employee_id' => $row['nik'] ?? $row['employee_id']],
            [
                'name'          => $row['nama'] ?? $row['name'],
                'email'         => $row['email'],
                'phone'         => $row['telepon'] ?? $row['phone'],
                'department_id' => $department ? $department->id : null,
                'position_id'   => $position ? $position->id : null,
                'status'        => ucfirst($row['status'] ?? 'Active'),
            ]
        );
    }
}
