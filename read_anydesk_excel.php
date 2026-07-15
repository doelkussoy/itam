<?php
require __DIR__.'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileType = 'Xlsx';
$inputFileName = 'D:\DOC\anydesk.xlsx';

if (!file_exists($inputFileName)) {
    echo "File not found: " . $inputFileName . "\n";
    exit;
}

$reader = IOFactory::createReader($inputFileType);
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($inputFileName);
$worksheet = $spreadsheet->getActiveSheet();

$rows = $worksheet->toArray(null, false, true, false);

echo "Rows count: " . count($rows) . "\n";
if (count($rows) > 0) {
    echo "Header: " . implode(" | ", $rows[0]) . "\n";
}
if (count($rows) > 1) {
    echo "Row 1: " . implode(" | ", $rows[1]) . "\n";
}
if (count($rows) > 2) {
    echo "Row 2: " . implode(" | ", $rows[2]) . "\n";
}
