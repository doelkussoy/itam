<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationDataSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            'Kantor Laboratorium Lt. 1',
            'Kantor Laboratorium Lt. 2',
            'Kantor Produksi Lt. 2',
            'Kantor Filling E1',
            'Kantor Filling F4',
            'Kantor Reaktor E3',
            'Kantor Produksi Botol D2',
            'Kantor Produksi Mulsa C',
            'Kantor Assembling H2',
            'Kantor Assembling I3',
            'Kantor Gudang RMT',
            'Kantor Gudang Botol',
            'Kantor Gudang Mulsa',
            'Kantor Gudang CFIF',
            'Kantor Gudang F1',
            'Kantor Gudang PRQ F2',
            'Kantor Gudang AUX F3',
            'Kantor Logistik',
            'Kantor HRD',
            'Kantor MTC (Maintenance)',
            'Kantor CF B1',
            'Kantor IF B4',
            'Kantor Methyl',
            'Gedung A3',
            'Gedung F5',
            'Gedung J',
            'Pos Security'
        ];

        foreach ($locations as $loc) {
            Location::firstOrCreate([
                'name' => $loc
            ], [
                'address'     => 'Pabrik Cikande',
                'description' => 'Lokasi ' . $loc,
            ]);
        }

        // Ensure existing records also have the address filled
        Location::whereNull('address')->orWhere('address', '')->update(['address' => 'Pabrik Cikande']);
    }
}
