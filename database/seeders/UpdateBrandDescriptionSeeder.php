<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class UpdateBrandDescriptionSeeder extends Seeder
{
    public function run()
    {
        $descriptions = [
            'Acer'                  => 'Acer Inc. – produsen perangkat komputer, laptop, monitor, dan aksesoris asal Taiwan.',
            'AMD'                   => 'Advanced Micro Devices – produsen prosesor (CPU) dan kartu grafis (GPU) asal Amerika Serikat.',
            'BenQ'                  => 'BenQ Corporation – produsen monitor, proyektor, dan periferal komputer asal Taiwan.',
            'BMAX'                  => 'BMAX – produsen mini PC dan laptop entry-level asal Tiongkok.',
            'Brother'               => 'Brother Industries – produsen printer, mesin fax, dan peralatan kantor asal Jepang.',
            'Dell'                  => 'Dell Technologies – produsen komputer, laptop, server, dan solusi IT enterprise asal Amerika Serikat.',
            'Epson'                 => 'Seiko Epson Corporation – produsen printer, scanner, dan proyektor asal Jepang.',
            'Hikvision'             => 'Hangzhou Hikvision – produsen kamera CCTV, NVR/DVR, dan sistem keamanan terkemuka asal Tiongkok.',
            'HP'                    => 'Hewlett-Packard – produsen komputer, laptop, printer, dan perangkat IT enterprise asal Amerika Serikat.',
            'Innovation'            => 'Innovation – produsen aksesoris komputer dan periferal.',
            'Intel'                 => 'Intel Corporation – produsen prosesor (CPU), chipset, dan solusi komputasi terkemuka asal Amerika Serikat.',
            'Klevv'                 => 'KLEVV – produsen modul memori RAM dan SSD berkualitas tinggi asal Korea Selatan.',
            'Lenovo'                => 'Lenovo Group Limited – produsen komputer, laptop, tablet, dan server terbesar di dunia, asal Tiongkok.',
            'LG'                    => 'LG Electronics – produsen monitor, TV, perangkat elektronik, dan solusi B2B asal Korea Selatan.',
            'Logitech'              => 'Logitech International – produsen mouse, keyboard, webcam, dan aksesoris komputer asal Swiss.',
            'MikroTik'              => 'Mikrotikls SIA (MikroTik) – produsen router, switch, dan perangkat jaringan berbasis RouterOS asal Latvia.',
            'MSI'                   => 'Micro-Star International – produsen motherboard, kartu grafis, laptop gaming, dan PC asal Taiwan.',
            'PowerColor'            => 'PowerColor (TUL Corporation) – produsen kartu grafis AMD Radeon asal Taiwan.',
            'Samsung'               => 'Samsung Electronics – produsen monitor, SSD, RAM, printer, dan perangkat elektronik asal Korea Selatan.',
            'Ubiquiti UniFi'        => 'Ubiquiti Inc. – produsen perangkat jaringan enterprise (access point, switch, router) asal Amerika Serikat.',
            'V-Gen'                 => 'V-Gen – produsen modul memori RAM dan SSD lokal asal Indonesia.',
            'Western Digital (WDC)' => 'Western Digital Corporation – produsen hard disk (HDD) dan solid state drive (SSD) asal Amerika Serikat.',
        ];

        foreach ($descriptions as $name => $desc) {
            $updated = Brand::where('name', $name)->update(['description' => $desc]);
            $this->command->info("Updated '$name' ($updated row(s))");
        }
    }
}
