<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$locations = App\Models\Location::withTrashed()->get();
echo "Total locations: " . $locations->count() . "\n";
foreach($locations as $loc) {
    echo "ID: {$loc->id}, Name: {$loc->name}, Address: {$loc->address}, Trashed: " . ($loc->trashed() ? 'Yes' : 'No') . "\n";
}
