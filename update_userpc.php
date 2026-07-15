<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Employee;
use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileType = 'Xlsx';
$inputFileName = 'D:\DOC\userpc.xlsx';

if (!file_exists($inputFileName)) {
    echo "File not found: " . $inputFileName . "\n";
    exit;
}

$reader = IOFactory::createReader($inputFileType);
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($inputFileName);
$worksheet = $spreadsheet->getActiveSheet();

$rows = $worksheet->toArray(null, false, true, false);

$updatedCount = 0;
$notFoundCount = 0;
$skippedCount = 0;

foreach ($rows as $index => $row) {
    if ($index === 0) continue; // Skip header

    $pic = trim($row[0] ?? '');
    $userRaw = trim($row[1] ?? '');
    $passRaw = trim($row[2] ?? '');

    if (empty($pic) || (empty($userRaw) && empty($passRaw))) continue;

    // Parse User
    $userParts = explode("\n", str_replace("\r", "", $userRaw));
    $finalUser = null;
    foreach ($userParts as $u) {
        $u = trim($u);
        if (empty($u)) continue;
        if (stripos($u, 'lokal') === false) {
            $finalUser = $u;
            break;
        }
    }

    // Parse Password
    $passParts = explode("\n", str_replace("\r", "", $passRaw));
    $finalPass = null;
    foreach ($passParts as $p) {
        $p = trim($p);
        if (empty($p)) continue;
        if (stripos($p, 'lokal') === false) {
            $finalPass = $p;
            break;
        }
    }

    if (empty($finalUser) && empty($finalPass)) {
        $skippedCount++;
        continue;
    }

    // Clean up Kosong
    if (strtolower($finalPass) === 'kosong') {
        $finalPass = null;
    }

    // Find Employee by Name
    $employee = Employee::where('name', 'LIKE', '%' . $pic . '%')->first();

    if ($employee) {
        $updateData = [];
        if (!empty($finalUser)) {
            $updateData['login_username'] = $finalUser;
        }
        if (!empty($finalPass)) {
            $updateData['login_password'] = $finalPass;
        }

        if (!empty($updateData)) {
            $employee->update($updateData);
            $updatedCount++;
        }
    } else {
        $notFoundCount++;
    }
}

echo "Updated: $updatedCount\n";
echo "Not Found: $notFoundCount\n";
echo "Skipped (Only Local): $skippedCount\n";
