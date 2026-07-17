<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Location;
use App\Models\Employee;
use App\Models\IpAddress;
use App\Models\Vlan;
use App\Models\SoftwareLicense;
use App\Models\PasswordVault;
use Carbon\Carbon;

class SampleITAMDataSeeder extends Seeder
{
    public function run()
    {
        // 0. Seed Default Settings
        $defaultSettings = [
            [
                'key' => 'app_name',
                'value' => 'ITAM Enterprise',
                'type' => 'text',
                'group' => 'general'
            ],
            [
                'key' => 'company_name',
                'value' => 'PT CBA Chemical Industry',
                'type' => 'text',
                'group' => 'general'
            ],
            [
                'key' => 'company_email',
                'value' => 'it.admin@cba.co.id',
                'type' => 'text',
                'group' => 'general'
            ],
            [
                'key' => 'smtp_host',
                'value' => 'smtp.mailtrap.io',
                'type' => 'text',
                'group' => 'email'
            ],
            [
                'key' => 'smtp_port',
                'value' => '2525',
                'type' => 'text',
                'group' => 'email'
            ],
            [
                'key' => 'smtp_username',
                'value' => 'null',
                'type' => 'text',
                'group' => 'email'
            ],
            [
                'key' => 'smtp_password',
                'value' => 'null',
                'type' => 'text',
                'group' => 'email'
            ],
            [
                'key' => 'email_notification',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'system'
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'system'
            ],
            [
                'key' => 'auto_backup',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'system'
            ]
        ];

        foreach ($defaultSettings as $setting) {
            \App\Models\Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }

        // 1. Seed VLANs
        $vlan2 = Vlan::firstOrCreate(['vlan_number' => 2], ['name' => 'VLAN Client', 'subnet' => '192.168.2.0/24', 'gateway' => '192.168.2.1', 'status' => 'Active']);
        $vlan5 = Vlan::firstOrCreate(['vlan_number' => 5], ['name' => 'VLAN Server', 'subnet' => '192.168.5.0/24', 'gateway' => '192.168.5.1', 'status' => 'Active']);
        $vlan6 = Vlan::firstOrCreate(['vlan_number' => 6], ['name' => 'VLAN CCTV', 'subnet' => '192.168.6.0/24', 'gateway' => '192.168.6.1', 'status' => 'Active']);

        // 2. Seed Software Licenses
        $empPic = Employee::first();
        $picId = $empPic ? $empPic->id : null;

        SoftwareLicense::firstOrCreate(['name' => 'Windows 11 Professional'], [
            'license_key' => 'W269N-WFGWX-YVC9B-4J6C9-T83GX',
            'expiry_date' => null,
            'total_seats' => 100,
            'pic_id' => $picId,
            'notes' => 'Volume license for office PCs'
        ]);

        SoftwareLicense::firstOrCreate(['name' => 'Office Home & Business 2021'], [
            'license_key' => 'MS-OFF21-BBBBB-CCCCC-DDDDD-EEEEE',
            'expiry_date' => null,
            'total_seats' => 50,
            'pic_id' => $picId,
            'notes' => 'Retail keys for operations staff'
        ]);

        SoftwareLicense::firstOrCreate(['name' => 'AnyDesk Premium'], [
            'license_key' => 'ANY-COMM-123-456-789',
            'expiry_date' => Carbon::now()->addDays(20), // Expirying soon warning!
            'total_seats' => 20,
            'pic_id' => $picId,
            'notes' => 'AnyDesk license for IT remote control'
        ]);

        // 3. Seed Password Vault entries
        PasswordVault::firstOrCreate(['device_name' => 'CCTV NVR Lab'], [
            'username' => 'admin',
            'encrypted_password' => 'CBA_Lab_Nvr_2026',
            'category' => 'CCTV',
            'notes' => 'Located in Laboratory server room rack'
        ]);

        PasswordVault::firstOrCreate(['device_name' => 'Switch Core CBA'], [
            'username' => 'admin',
            'encrypted_password' => 'switch_core_secret_password',
            'category' => 'Switch',
            'notes' => 'Backup config on \\\\cba-nas\\configs\\'
        ]);

        PasswordVault::firstOrCreate(['device_name' => 'IT Support Email'], [
            'username' => 'support@cba.co.id',
            'encrypted_password' => 'Sup_CBA_Pabrik_99',
            'category' => 'Email',
            'notes' => 'Shared mailbox password'
        ]);

        // 4. Load or check Categories/Brands/Locations
        $compCat = Category::where('name', 'Komputer')->first();
        $laptopCat = Category::where('name', 'Laptop')->first();
        $printCat = Category::where('name', 'Printer')->first();
        $switchCat = Category::where('name', 'Switch')->first();
        $apCat = Category::where('name', 'Access Point')->first();
        $cctvCat = Category::where('name', 'CCTV')->first();

        // Create categories if they don't exist
        if (!$compCat) $compCat = Category::create(['name' => 'Komputer', 'description' => 'PC Client']);
        if (!$laptopCat) $laptopCat = Category::create(['name' => 'Laptop', 'description' => 'Laptop Client']);
        if (!$printCat) $printCat = Category::create(['name' => 'Printer', 'description' => 'Printer Client']);
        if (!$switchCat) $switchCat = Category::create(['name' => 'Switch', 'description' => 'Switch Network']);
        if (!$apCat) $apCat = Category::create(['name' => 'Access Point', 'description' => 'WiFi Access Point']);
        if (!$cctvCat) $cctvCat = Category::create(['name' => 'CCTV', 'description' => 'Camera CCTV']);

        $brandHP = Brand::firstOrCreate(['name' => 'HP']);
        $brandDell = Brand::firstOrCreate(['name' => 'Dell']);
        $brandEpson = Brand::firstOrCreate(['name' => 'Epson']);
        $brandMikroTik = Brand::firstOrCreate(['name' => 'MikroTik']);
        $brandUniFi = Brand::firstOrCreate(['name' => 'Ubiquiti UniFi']);
        $brandHikvision = Brand::firstOrCreate(['name' => 'Hikvision']);

        $locLab = Location::firstOrCreate(['name' => 'Kantor Laboratorium Lt. 2']);
        $locOffice = Location::firstOrCreate(['name' => 'Kantor HRD']);
        $locGudang = Location::firstOrCreate(['name' => 'Kantor Gudang Botol']);
        $locSec = Location::firstOrCreate(['name' => 'Pos Security']);

        // Update some employees with locations/supervisor/tech connection
        $allEmps = Employee::all();
        if ($allEmps->count() >= 2) {
            $supervisor = $allEmps[0];
            $allEmps->each(function($emp, $idx) use ($supervisor, $locOffice) {
                if ($idx > 0) {
                    $emp->update([
                        'supervisor_id' => $supervisor->id,
                        'location_id' => $locOffice->id,
                        'extension' => '10' . $idx,
                        'anydesk_id' => '98765432' . $idx,
                        'anydesk_password' => 'pass_any_' . $idx
                    ]);
                }
            });
        }

        // 5. Seed Assets with Specification details
        
        // Asset 1: PC
        $asset1 = Asset::firstOrCreate(['asset_tag' => 'CBA-PC-0001'], [
            'name' => 'HP ProDesk 600 G6 MT',
            'serial_number' => 'SGH0001PC',
            'category_id' => $compCat->id,
            'brand_id' => $brandHP->id,
            'location_id' => $locLab->id,
            'date_received' => Carbon::now()->subYears(2)->format('Y-m-d'), // 2 years old
            'delivery_order_number' => 'DO-HP-2024-001',
            'warranty_months' => 36, // active warranty
            'status' => 'Assigned',
            'notes' => 'Laboratory computer client'
        ]);
        $asset1->computer()->updateOrCreate([], [
            'cpu' => 'Intel Core i5-10500 @ 3.10GHz',
            'ram' => 16,
            'ssd' => 512,
            'hdd' => 1000,
            'gpu' => 'Intel UHD Graphics 630',
            'os' => 'Windows 11 Pro',
            'office' => 'Office Home & Business 2021'
        ]);

        // Asset 2: Laptop (Expiring Warranty)
        $asset2 = Asset::firstOrCreate(['asset_tag' => 'CBA-LT-0001'], [
            'name' => 'Dell Latitude 3420',
            'serial_number' => 'DELL888LT',
            'category_id' => $laptopCat->id,
            'brand_id' => $brandDell->id,
            'location_id' => $locOffice->id,
            'date_received' => Carbon::now()->subMonths(11)->subDays(15)->format('Y-m-d'), // Expires in 15 days!
            'delivery_order_number' => 'DO-DELL-2025-09',
            'warranty_months' => 12,
            'status' => 'Assigned',
            'notes' => 'HRD Staff Laptop'
        ]);
        $asset2->computer()->updateOrCreate([], [
            'cpu' => 'Intel Core i5-1135G7 @ 2.40GHz',
            'ram' => 8,
            'ssd' => 256,
            'hdd' => 0,
            'gpu' => 'Intel Iris Xe Graphics',
            'os' => 'Windows 10 Pro',
            'office' => 'Office Home & Business 2019'
        ]);

        // Asset 3: Printer
        $asset3 = Asset::firstOrCreate(['asset_tag' => 'CBA-PR-0001'], [
            'name' => 'Epson L3110 Multi',
            'serial_number' => 'EPSON-L3110-XYZ',
            'category_id' => $printCat->id,
            'brand_id' => $brandEpson->id,
            'location_id' => $locOffice->id,
            'date_received' => Carbon::now()->subYears(4)->format('Y-m-d'), // 4 years old (expired warranty)
            'delivery_order_number' => 'DO-EPS-2022',
            'warranty_months' => 12,
            'status' => 'Available',
            'notes' => 'Epson desk printer'
        ]);
        $asset3->printer()->updateOrCreate([], [
            'type' => 'Inkjet',
            'connection_type' => 'USB',
            'has_scanner' => true,
            'counter_print' => 12450,
            'toner_status' => 'BK: 60%, C: 40%, M: 40%, Y: 50%',
            'drum_status' => 'OK'
        ]);

        // Asset 4: Network Switch
        $asset4 = Asset::firstOrCreate(['asset_tag' => 'CBA-SW-0001'], [
            'name' => 'MikroTik CRS326-24G-2S+RM',
            'serial_number' => 'MTIK-CRS326-XYZ',
            'category_id' => $switchCat->id,
            'brand_id' => $brandMikroTik->id,
            'location_id' => $locLab->id,
            'date_received' => Carbon::now()->subYears(5)->format('Y-m-d'), // 5 years old
            'delivery_order_number' => 'DO-MT-2021',
            'warranty_months' => 12,
            'status' => 'Available',
            'notes' => 'Core switch laboratory rack'
        ]);
        $asset4->networkDetail()->updateOrCreate([], [
            'firmware' => 'RouterOS v7.8',
            'port_count' => 24,
            'active_ports' => 18,
            'backup_config_path' => '\\\\cba-nas\\configs\\switches\\crs326-lab.backup',
            'ssid' => null,
            'wifi_password' => null,
            'controller' => null
        ]);

        // Asset 5: Access Point
        $asset5 = Asset::firstOrCreate(['asset_tag' => 'CBA-AP-0001'], [
            'name' => 'UniFi AP AC Lite',
            'serial_number' => 'UBNT-AP-LITE-1',
            'category_id' => $apCat->id,
            'brand_id' => $brandUniFi->id,
            'location_id' => $locOffice->id,
            'date_received' => Carbon::now()->subYears(1)->format('Y-m-d'), // 1 year old
            'delivery_order_number' => 'DO-AP-25',
            'warranty_months' => 24,
            'status' => 'Available',
            'notes' => 'Ceiling mounted AP HRD room'
        ]);
        $asset5->networkDetail()->updateOrCreate([], [
            'firmware' => 'AP-v6.5.28',
            'port_count' => 1,
            'active_ports' => 1,
            'backup_config_path' => null,
            'ssid' => 'CBA Chemical Industry Wifi',
            'wifi_password' => 'cba_wifi_pabrik_2026',
            'controller' => '192.168.5.5 (UniFi Server)'
        ]);

        // Asset 6: CCTV Camera
        $asset6 = Asset::firstOrCreate(['asset_tag' => 'CBA-CAM-0001'], [
            'name' => 'Hikvision DS-2CD1123G0-I',
            'serial_number' => 'HIK-CAM-11001',
            'category_id' => $cctvCat->id,
            'brand_id' => $brandHikvision->id,
            'location_id' => $locSec->id,
            'date_received' => Carbon::now()->subYears(11)->format('Y-m-d'), // 11 years old! (over 10 years)
            'delivery_order_number' => 'DO-HIK-2015',
            'warranty_months' => 36,
            'status' => 'Available',
            'notes' => 'Pos Security main gate dome camera'
        ]);
        $asset6->cctv()->updateOrCreate([], [
            'nvr_channel' => 1,
            'firmware' => 'HIK-IPC-V5.5.82',
            'username' => 'admin',
            'password' => 'Hikvision_CCTV_Gate_26'
        ]);

        // 6. Seed IP Addresses linked to VLANs and Assets
        $emp1 = Employee::first();
        if (!$emp1) {
            $defaultDepartment = \App\Models\Department::firstOrCreate(['name' => 'General'], ['description' => 'General Department']);
            $emp1 = Employee::create([
                'employee_id' => 'EMP-DEFAULT',
                'name' => 'Default Employee',
                'status' => 'Active'
            ]);
        }
        $empId = $emp1->id;
        
        IpAddress::firstOrCreate(['ip_address' => '192.168.2.10'], [
            'mac_address' => 'AA:BB:CC:11:22:33',
            'asset_id' => $asset1->id,
            'employee_id' => $empId,
            'vlan_id' => $vlan2->id,
            'gateway' => '192.168.2.1',
            'dns' => '8.8.8.8, 8.8.4.4',
            'status' => 'Used',
            'notes' => 'Assigned to lab PC'
        ]);

        IpAddress::firstOrCreate(['ip_address' => '192.168.2.15'], [
            'mac_address' => 'AA:BB:CC:44:55:66',
            'asset_id' => $asset2->id,
            'employee_id' => $empId,
            'vlan_id' => $vlan2->id,
            'gateway' => '192.168.2.1',
            'dns' => '8.8.8.8, 8.8.4.4',
            'status' => 'Used',
            'notes' => 'Assigned to HRD laptop'
        ]);

        IpAddress::firstOrCreate(['ip_address' => '192.168.5.50'], [
            'mac_address' => '11:22:33:AA:BB:CC',
            'asset_id' => $asset4->id,
            'employee_id' => null,
            'vlan_id' => $vlan5->id,
            'gateway' => '192.168.5.1',
            'dns' => '8.8.8.8',
            'status' => 'Used',
            'notes' => 'Switch Core Management IP'
        ]);

        IpAddress::firstOrCreate(['ip_address' => '192.168.6.100'], [
            'mac_address' => '44:55:66:DD:EE:FF',
            'asset_id' => $asset6->id,
            'employee_id' => null,
            'vlan_id' => $vlan6->id,
            'gateway' => '192.168.6.1',
            'dns' => '8.8.8.8',
            'status' => 'Used',
            'notes' => 'Pos Security CCTV camera IP'
        ]);
        
        // 7. Seed assignments history
        $asset1->assignments()->create([
            'employee_id' => $empId,
            'assigned_date' => Carbon::now()->subYears(2)->format('Y-m-d'),
            'status' => 'Assigned',
            'notes' => 'Initial assign on receive'
        ]);

        // 8. Seed some maintenance logs
        $asset1->maintenances()->create([
            'type' => 'Upgrade',
            'description' => 'Upgrade RAM dari 8GB ke 16GB dan install SSD 512GB',
            'cost' => 850000,
            'start_date' => Carbon::now()->subMonths(6)->format('Y-m-d'),
            'end_date' => Carbon::now()->subMonths(6)->addDays(1)->format('Y-m-d'),
            'status' => 'Completed'
        ]);

        $asset1->maintenances()->create([
            'type' => 'Routine',
            'description' => 'Cleaning hardware & replace thermal paste',
            'cost' => 150000,
            'start_date' => Carbon::now()->subMonths(1)->format('Y-m-d'),
            'end_date' => Carbon::now()->subMonths(1)->format('Y-m-d'),
            'status' => 'Completed'
        ]);
    }
}
