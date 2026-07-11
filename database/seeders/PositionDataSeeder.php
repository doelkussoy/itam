<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Position;

class PositionDataSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['IT', 'IT Staff'],
            ['IT', 'IT Supervisor'],
            ['IT', 'IT Technician'],
            ['Production', 'Production Operator'],
            ['Production', 'Production Supervisor'],
            ['Production', 'Production Manager'],
            ['QC', 'QC Staff'],
            ['QC', 'QC Inspector'],
            ['Warehouse', 'Warehouse Staff'],
            ['Warehouse', 'Warehouse Supervisor'],
            ['Engineering', 'Engineer'],
            ['Engineering', 'Engineering Supervisor'],
            ['Maintenance', 'Technician'],
            ['HRD', 'HR Staff'],
            ['Purchasing', 'Purchasing Staff'],
        ];

        foreach ($data as $row) {
            $divName = $row[0];
            $posName = $row[1];

            // Map some of the generic divisions to the specific ones if possible, 
            // or just create them so the user gets exactly what they asked for.
            $department = Department::firstOrCreate(['name' => $divName]);

            Position::firstOrCreate([
                'department_id' => $department->id,
                'name' => $posName,
            ], [
                'description' => 'Posisi ' . $posName . ' untuk divisi ' . $divName
            ]);
        }
    }
}
