<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\Department;

class UpdatePositionDepartmentSeeder extends Seeder
{
    public function run()
    {
        // Map each position name to its correct department name
        $positionDeptMap = [
            // Production roles
            'Production Team Leader'                    => 'Production C11',
            'Operator'                                  => 'Production C11',
            'Foreman'                                   => 'Production C11',
            'Foreman Assistant'                         => 'Production C11',
            'Operator Engineering'                      => 'Production C11',
            'Operator Enginering'                       => 'Production C11',
            'Officer C11 Mixing'                        => 'Production C11',
            'Adm  Production PL. MLS & ASS Officer'     => 'PPIC',
            'Production Officer'                        => 'Production C11',

            // Engineering roles
            'Foreman Engineering'                       => 'Engineering Pestisida',
            'Engineering Team Leader'                   => 'Engineering Plastik',
            'Supervisor Engineering'                    => 'Engineering Pestisida',
            'Plastic Technical & Development Manager'   => 'Engineering Plastik',

            // QC / QA roles
            'QC Incoming/Outgoing Officer'              => 'QC',
            'QA Officer'                                => 'QA',

            // HSE roles
            'HSE Officer'                               => 'HSE',

            // R&D roles
            'Officer R & D'                             => 'R&D',

            // Management / General roles that apply across departments
            // Keeping these in a sensible department
            'Supervisor'                                => 'HC & GA',
            'Manager'                                   => 'HC & GA',
            'Dept Head'                                 => 'HC & GA',
            'Dept. Head'                                => 'HC & GA',
            'Ast Dept Head'                             => 'HC & GA',
            'Ast. Dept Head'                            => 'HC & GA',
            'Office Boy'                                => 'HC & GA',
            'Officer'                                   => 'HC & GA',
            'Team Leader'                               => 'HC & GA',
        ];

        foreach ($positionDeptMap as $positionName => $deptName) {
            $department = Department::where('name', $deptName)->first();
            if (!$department) {
                $this->command->warn("Department not found: $deptName");
                continue;
            }

            $updated = Position::where('name', $positionName)
                ->update(['department_id' => $department->id]);

            $this->command->info("Updated '$positionName' → '$deptName' ($updated row(s))");
        }
    }
}
