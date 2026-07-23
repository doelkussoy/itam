<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\IpAddress;

$ips = IpAddress::whereNull('asset_id')->whereNotNull('notes')->where('notes', '!=', '')->get();
echo "Found " . count($ips) . " IPs with notes but no asset_id\n";
foreach($ips->take(10) as $ip) {
    echo "IP: $ip->ip_address, Notes: $ip->notes, Status: $ip->status\n";
}


echo "Data normalization complete.\n";
