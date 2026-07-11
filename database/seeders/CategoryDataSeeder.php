<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoryDataSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Komputer',
            'Laptop',
            'Mini PC',
            'Thin Client',
            'Server',
            'Monitor',
            'Printer',
            'Scanner',
            'Mesin Fax',
            'Switch',
            'UPS',
            'Keyboard',
            'Mouse',
            'HDD',
            'SSD',
            'RAM',
            'Motherboard'
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate([
                'name' => $cat
            ], [
                'description' => 'Kategori ' . $cat
            ]);
        }
    }
}
