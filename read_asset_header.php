<?php
require __DIR__.'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

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

echo "Rows count: " . count($rows) . "\n";
if (count($rows) > 0) {
    echo "Header:\n";
    foreach ($rows[0] as $index => $col) {
        echo "[$index] => $col\n";
    }
}
if (count($rows) > 1) {
    echo "Row 1:\n";
    foreach ($rows[1] as $index => $col) {
        echo "[$index] => $col\n";
    }
}
