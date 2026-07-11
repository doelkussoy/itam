<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SyncEmployeeDepartmentSeeder extends Seeder
{
    /**
     * Sync each employee's department_id from their linked position's department_id.
     * Employees with no position, or positions with no department, are left unchanged (null).
     */
    public function run()
    {
        // Get all employees that have a position_id set
        $employees = DB::table('employees')
            ->whereNull('deleted_at')
            ->whereNotNull('position_id')
            ->select('id', 'position_id', 'department_id')
            ->get();

        $updated = 0;
        $skipped = 0;

        foreach ($employees as $employee) {
            // Look up the department_id from the position
            $position = DB::table('positions')
                ->whereNull('deleted_at')
                ->where('id', $employee->position_id)
                ->select('department_id')
                ->first();

            if (!$position || !$position->department_id) {
                $skipped++;
                continue;
            }

            // Only update if department is not already correct
            if ($employee->department_id !== $position->department_id) {
                DB::table('employees')
                    ->where('id', $employee->id)
                    ->update(['department_id' => $position->department_id]);
                $updated++;
            }
        }

        $this->command->info("Synced $updated employee(s) department from position. Skipped $skipped (no position/department).");
    }
}
