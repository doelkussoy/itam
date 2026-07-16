<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Department;
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
        // Try to find department by name
        $deptName = $row['department_name'] ?? $row['divisi'] ?? $row['departemen'] ?? $row['department'] ?? '';
        $department = null;
        if (!empty($deptName)) {
            $department = Department::where('name', 'like', $deptName)->first();
        }

        // Supervisor mapping
        $supervisorName = $row['supervisor_name'] ?? $row['supervisor'] ?? null;
        $supervisor = null;
        if (!empty($supervisorName)) {
            $supervisor = Employee::where('name', 'like', $supervisorName)->first();
        }

        $nik = $row['nik'] ?? $row['employee_id'] ?? null;
        if (!$nik) return null; // Skip row if no NIK

        $name = $row['employee_name'] ?? $row['nama'] ?? $row['name'] ?? 'Unknown';

        // Update or create employee
        $employee = Employee::withTrashed()->where('employee_id', $nik)->first();
        $data = [
            'name'          => $name,
            'email'         => $row['email'] ?? null, // Allow missing email
            'phone'         => $row['phone_number_employee'] ?? $row['telepon'] ?? $row['phone'] ?? null,
            'department_id' => $department ? $department->id : null,
            'supervisor_id' => $supervisor ? $supervisor->id : null,
            'status'        => ucfirst($row['status'] ?? 'Active'),
        ];

        if ($employee) {
            if ($employee->trashed()) $employee->restore();
            $employee->update($data);
            return $employee;
        }

        $data['employee_id'] = $nik;
        return Employee::create($data);
    }
}
