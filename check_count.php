<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cctv = \App\Models\Category::where('name', 'CCTV')->first();
$printer = \App\Models\Category::where('name', 'Printer')->first();

echo "CCTV count: " . ($cctv ? \App\Models\Asset::where('category_id', $cctv->id)->count() : 'null') . "\n";
echo "Printer count: " . ($printer ? \App\Models\Asset::where('category_id', $printer->id)->count() : 'null') . "\n";
