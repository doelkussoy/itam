<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class UpdateDepartmentSeeder extends Seeder
{
    public function run()
    {
        $descriptions = [
            'Production C11' => 'Divisi Produksi Pabrik C11',
            'Production C12' => 'Divisi Produksi Pabrik C12',
            'Production C13' => 'Divisi Produksi Pabrik C13',
            'PPIC' => 'Production Planning and Inventory Control',
            'Gudang RMT' => 'Pengelolaan Raw Material (Bahan Baku)',
            'Finish Goods' => 'Pengelolaan Barang Jadi',
            'Logistik' => 'Divisi Logistik dan Distribusi',
            'QA' => 'Quality Assurance (Penjaminan Mutu)',
            'QC' => 'Quality Control (Pengendalian Mutu)',
            'Engineering' => 'Divisi Teknik dan Pemeliharaan Umum',
            'Engineering Pestisida' => 'Pemeliharaan dan Teknik Pabrik Pestisida',
            'Engineering Plastik' => 'Pemeliharaan dan Teknik Pabrik Plastik',
            'IT' => 'Dukungan Teknologi Informasi dan Sistem',
            'HC & GA' => 'Human Capital & General Affairs',
            'HSE' => 'Health, Safety, and Environment',
            'R&D' => 'Research and Development (Riset & Pengembangan)',
            'Security' => 'Keamanan dan Ketertiban Fasilitas',
            'General' => 'General Department / Administrasi Umum',
            'Production' => 'Divisi Produksi Umum',
            'Warehouse' => 'Gudang Penyimpanan',
            'Maintenance' => 'Divisi Pemeliharaan Aset',
            'HRD' => 'Human Resources Development (Pengembangan Sumber Daya Manusia)',
            'Purchasing' => 'Divisi Pembelian dan Pengadaan'
        ];

        foreach ($descriptions as $name => $desc) {
            Department::where('name', $name)->update(['description' => $desc]);
        }
    }
}
