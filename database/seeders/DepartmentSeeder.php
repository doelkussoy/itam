<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Production C11',
            'Production C12',
            'Production C13',
            'PPIC',
            'Gudang RMT',
            'Finish Goods',
            'Logistik',
            'QA',
            'QC',
            'Engineering Pestisida',
            'Engineering Plastik',
            'IT',
            'HC & GA',
            'HSE',
            'R&D',
            'Security'
        ];

        foreach ($departments as $dept) {
            \App\Models\Department::firstOrCreate(['name' => $dept]);
        }
    }
}
