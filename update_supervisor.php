<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Employee;
use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileType = 'Xlsx';
$inputFileName = 'D:\DOC\karyawan.xlsx';

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
$notFoundEmpCount = 0;
$notFoundSupCount = 0;

foreach ($rows as $index => $row) {
    if ($index === 0) continue; // Skip header

    $nik = trim($row[0] ?? '');
    $supName = trim($row[6] ?? '');

    if (empty($nik) || empty($supName) || strtolower($supName) === 'null') continue;

    // Find Employee by NIK
    $employee = Employee::where('employee_id', $nik)->first();

    if ($employee) {
        // Find Supervisor by Name
        $supervisor = Employee::where('name', $supName)->first();
        if ($supervisor) {
            $employee->update([
                'supervisor_id' => $supervisor->id,
            ]);
            $updatedCount++;
        } else {
            $notFoundSupCount++;
        }
    } else {
        $notFoundEmpCount++;
    }
}

echo "Updated: $updatedCount\n";
echo "Not Found Employee (by NIK): $notFoundEmpCount\n";
echo "Not Found Supervisor (by Name): $notFoundSupCount\n";
