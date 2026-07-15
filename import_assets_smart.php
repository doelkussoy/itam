<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Asset;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Location;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

$inputFileType = 'Xlsx';
$inputFileName = 'D:\DOC\asset.xlsx';

if (!file_exists($inputFileName)) {
    echo "File not found: " . $inputFileName . "\n";
    exit;
}

$reader = IOFactory::createReader($inputFileType);
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($inputFileName);
$worksheet = $spreadsheet->getActiveSheet();
$rows = $worksheet->toArray(null, false, true, false);

$brands = ['BROTHER', 'LENOVO', 'HP', 'DELL', 'ASUS', 'ACER', 'EPSON', 'CANON', 'SAMSUNG', 'LG', 'APC', 'MIKROTIK', 'CISCO', 'TP-LINK', 'D-LINK', 'PANASONIC', 'SONY', 'LOGITECH', 'MICROSOFT'];
$categories = [
    'PC' => ['pc ', 'komputer', 'cpu', 'desktop'],
    'Laptop' => ['laptop', 'notebook'],
    'Printer' => ['printer'],
    'Scanner' => ['scanner'],
    'Mesin Fax' => ['fax'],
    'Monitor' => ['monitor', 'lcd', 'led', 'display'],
    'CCTV' => ['cctv', 'kamera', 'camera'],
    'UPS' => ['ups'],
    'Server' => ['server'],
    'Network' => ['switch', 'router', 'access point', 'hub'],
    'TV' => ['tv', 'televisi'],
    'Proyektor' => ['proyektor', 'projector'],
    'Software' => ['software', 'lisensi', 'license', 'windows', 'office', 'antivirus']
];

$addedCount = 0;

// Get the last asset tag number to increment
$lastAsset = Asset::orderBy('id', 'desc')->first();
$lastTagNum = 0;
if ($lastAsset && preg_match('/AST-(\d+)/', $lastAsset->asset_tag, $matches)) {
    $lastTagNum = (int)$matches[1];
}

foreach ($rows as $index => $row) {
    if ($index === 0) continue; // Skip header

    $desc = trim($row[0] ?? '');
    if (empty($desc)) continue;

    $usageDateRaw = trim($row[1] ?? '');
    $invoice = trim($row[2] ?? '');
    $purchaseDateRaw = trim($row[3] ?? '');
    $po = trim($row[4] ?? '');

    // Parse Quantity
    $qty = 1;
    $cleanDesc = $desc;
    if (preg_match('/^(\d+)\s*(Unit|Pcs|Buah|Set|Pck)\s*(.*)/i', $desc, $matches)) {
        $qty = (int)$matches[1];
        $cleanDesc = trim($matches[3]);
    }
    
    // Fallback if no quantity matched but we want to clean it
    if (empty($cleanDesc)) $cleanDesc = $desc;

    // Detect Brand
    $detectedBrand = null;
    foreach ($brands as $b) {
        if (stripos($cleanDesc, $b) !== false) {
            $detectedBrand = $b;
            break;
        }
    }

    // Detect Category
    $detectedCategory = 'Uncategorized';
    foreach ($categories as $catName => $keywords) {
        foreach ($keywords as $kw) {
            if (stripos($cleanDesc, $kw) !== false) {
                $detectedCategory = $catName;
                break 2;
            }
        }
    }

    // Find/Create Brand & Category
    $brandId = null;
    if ($detectedBrand) {
        $brandObj = Brand::firstOrCreate(['name' => $detectedBrand]);
        $brandId = $brandObj->id;
    }
    $catObj = Category::firstOrCreate(['name' => $detectedCategory]);
    $catId = $catObj->id;

    // Default Location
    $locObj = Location::firstOrCreate(['name' => 'PABRIK']);
    $locId = $locObj->id;

    // Parse Date
    $dateReceived = null;
    // Prefer Purchase Date, then Usage Date
    $dateStr = $purchaseDateRaw;
    if (empty($dateStr) || $dateStr == '-' || $dateStr == '-, -') {
        $dateStr = $usageDateRaw;
    }
    
    if (!empty($dateStr) && $dateStr != '-' && $dateStr != '-, -') {
        try {
            // "23-APR-08" -> d-M-y
            $dateReceived = Carbon::createFromFormat('d-M-y', $dateStr)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                $dateReceived = Carbon::parse($dateStr)->format('Y-m-d');
            } catch (\Exception $e2) {
                $dateReceived = null;
            }
        }
    }

    for ($i = 0; $i < $qty; $i++) {
        $lastTagNum++;
        $assetTag = 'AST-' . str_pad($lastTagNum, 5, '0', STR_PAD_LEFT);

        Asset::create([
            'asset_tag' => $assetTag,
            'name' => $cleanDesc,
            'category_id' => $catId,
            'brand_id' => $brandId,
            'location_id' => $locId,
            'date_received' => $dateReceived,
            'delivery_order_number' => !empty($po) ? $po : (!empty($invoice) ? $invoice : null),
            'status' => 'Available',
        ]);
        $addedCount++;
    }
}

echo "Successfully imported $addedCount assets.\n";
