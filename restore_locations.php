<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$restored = App\Models\Location::onlyTrashed()->where('address', 'like', '%PABRIK%')->restore();
echo "Restored: " . $restored . " locations.\n";
