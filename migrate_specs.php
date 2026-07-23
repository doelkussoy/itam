<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Asset;

// 1. Define specifications for categories
$specsMap = [
    'Computer' => [
        ['name' => 'cpu', 'label' => 'CPU Specification', 'type' => 'text'],
        ['name' => 'ram', 'label' => 'RAM Size (GB)', 'type' => 'number'],
        ['name' => 'gpu', 'label' => 'GPU Specification', 'type' => 'text'],
        ['name' => 'ssd', 'label' => 'SSD Size (GB)', 'type' => 'number'],
        ['name' => 'hdd', 'label' => 'HDD Size (GB)', 'type' => 'number'],
        ['name' => 'os', 'label' => 'Operating System', 'type' => 'text'],
        ['name' => 'office', 'label' => 'Office Suite', 'type' => 'text'],
    ],
    'Printer' => [
        ['name' => 'printer_type', 'label' => 'Printer Type', 'type' => 'text'],
        ['name' => 'connection_type', 'label' => 'Connection', 'type' => 'text'],
        ['name' => 'has_scanner', 'label' => 'Has Scanner (Yes/No)', 'type' => 'text'],
        ['name' => 'counter_print', 'label' => 'Initial Counter Page', 'type' => 'number'],
        ['name' => 'toner_status', 'label' => 'Toner/Cartridge Status', 'type' => 'text'],
        ['name' => 'drum_status', 'label' => 'Drum Status', 'type' => 'text'],
    ],
    'Monitor' => [
        ['name' => 'monitor_size', 'label' => 'Display Size (Inches)', 'type' => 'number'],
    ],
    'Network' => [
        ['name' => 'network_firmware', 'label' => 'Firmware Version', 'type' => 'text'],
        ['name' => 'controller', 'label' => 'AP Controller', 'type' => 'text'],
        ['name' => 'port_count', 'label' => 'Port Count', 'type' => 'number'],
        ['name' => 'active_ports', 'label' => 'Active Ports', 'type' => 'number'],
        ['name' => 'ssid', 'label' => 'SSID', 'type' => 'text'],
        ['name' => 'wifi_password', 'label' => 'Wi-Fi Password', 'type' => 'text'],
        ['name' => 'backup_config_path', 'label' => 'Config Backup Path', 'type' => 'text'],
    ],
    'CCTV' => [
        ['name' => 'nvr_channel', 'label' => 'NVR Channel', 'type' => 'text'],
        ['name' => 'cctv_firmware', 'label' => 'Firmware Version', 'type' => 'text'],
        ['name' => 'cctv_username', 'label' => 'NVR Account User', 'type' => 'text'],
        ['name' => 'cctv_password', 'label' => 'Account Password', 'type' => 'text'],
    ]
];

foreach ($specsMap as $catName => $specs) {
    $category = Category::where('name', $catName)->first();
    if ($category) {
        $category->update(['spec_definitions' => $specs]);
        echo "Updated category: $catName\n";
    }
}

// 2. Migrate data from relation tables to spec_data json column
$assets = Asset::with(['computer', 'printer', 'monitor', 'networkDetail', 'cctv', 'category'])->get();

$count = 0;
foreach ($assets as $asset) {
    $specData = is_array($asset->spec_data) ? $asset->spec_data : [];
    $catName = $asset->category ? $asset->category->name : '';

    if ($catName === 'Computer' && $asset->computer) {
        $specData['cpu'] = $asset->computer->cpu;
        $specData['ram'] = $asset->computer->ram;
        $specData['gpu'] = $asset->computer->gpu;
        $specData['ssd'] = $asset->computer->ssd;
        $specData['hdd'] = $asset->computer->hdd;
        $specData['os'] = $asset->computer->os;
        $specData['office'] = $asset->computer->office;
    } elseif ($catName === 'Printer' && $asset->printer) {
        $specData['printer_type'] = $asset->printer->type;
        $specData['connection_type'] = $asset->printer->connection_type;
        $specData['has_scanner'] = $asset->printer->has_scanner ? 'Yes' : 'No';
        $specData['counter_print'] = $asset->printer->counter_print;
        $specData['toner_status'] = $asset->printer->toner_status;
        $specData['drum_status'] = $asset->printer->drum_status;
    } elseif ($catName === 'Monitor' && $asset->monitor) {
        $specData['monitor_size'] = $asset->monitor->size;
    } elseif ($catName === 'Network' && $asset->networkDetail) {
        $specData['network_firmware'] = $asset->networkDetail->firmware;
        $specData['controller'] = $asset->networkDetail->controller;
        $specData['port_count'] = $asset->networkDetail->port_count;
        $specData['active_ports'] = $asset->networkDetail->active_ports;
        $specData['ssid'] = $asset->networkDetail->ssid;
        $specData['wifi_password'] = $asset->networkDetail->wifi_password;
        $specData['backup_config_path'] = $asset->networkDetail->backup_config_path;
    } elseif ($catName === 'CCTV' && $asset->cctv) {
        $specData['nvr_channel'] = $asset->cctv->nvr_channel;
        $specData['cctv_firmware'] = $asset->cctv->firmware;
        $specData['cctv_username'] = $asset->cctv->username;
        $specData['cctv_password'] = $asset->cctv->password;
    }

    if (!empty($specData)) {
        $asset->update(['spec_data' => $specData]);
        $count++;
    }
}

echo "Migrated spec_data for $count assets.\n";
