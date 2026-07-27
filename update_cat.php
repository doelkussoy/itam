<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cat = \App\Models\Category::where('name', 'CCTV')->first();
if ($cat) {
    $cat->name = 'Printer';
    $cat->save();
    echo "Category updated successfully.\n";
} else {
    echo "Category CCTV not found.\n";
}
