<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Department;

class ImportEmployeesSeeder extends Seeder
{
    public function run()
    {
        $json = file_get_contents(base_path('employees.json'));
        $data = json_decode($json, true);

        $count = 0;
        foreach ($data as $row) {
            $employeeId = $row['Employee Id'] ?? null;
            $name = $row['Employee Name'] ?? null;
            $positionTitle = $row['Position Title'] ?? null;
            
            if (!$employeeId || !$name) continue;

            $defaultDepartment = Department::firstOrCreate(['name' => 'General'], ['description' => 'General Department']);

            $position = null;
            if (!empty($positionTitle)) {
                $position = Position::firstOrCreate([
                    'name' => $positionTitle
                ], [
                    'department_id' => $defaultDepartment->id,
                    'description' => 'Posisi ' . $positionTitle
                ]);
            }

            Employee::updateOrCreate([
                'employee_id' => $employeeId
            ], [
                'name' => $name,
                'position_id' => $position ? $position->id : null,
                'status' => 'Active'
            ]);
            
            $count++;
        }
        
        echo "Successfully imported $count employees.\n";
    }
}
