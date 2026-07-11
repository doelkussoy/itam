<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandDataSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            'Acer',
            'AMD',
            'BenQ',
            'BMAX',
            'Brother',
            'Epson',
            'HP',
            'Innovation',
            'Intel',
            'Klevv',
            'Lenovo',
            'LG',
            'Logitech',
            'MSI',
            'PowerColor',
            'Samsung',
            'V-Gen',
            'Western Digital (WDC)'
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate([
                'name' => $brand
            ], [
                'description' => 'Merek ' . $brand
            ]);
        }
    }
}
