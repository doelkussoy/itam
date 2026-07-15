<?php
require __DIR__.'/vendor/autoload.php';

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

echo "Checking for 'lokal'...\n";
foreach ($rows as $index => $row) {
    if ($index === 0) continue;
    $pic = trim($row[0] ?? '');
    $user = trim($row[1] ?? '');
    $pass = trim($row[2] ?? '');
    
    if (stripos($pic, 'lokal') !== false || stripos($user, 'lokal') !== false || stripos($pass, 'lokal') !== false) {
        echo "Row $index: $pic | $user | $pass\n";
    }
}
