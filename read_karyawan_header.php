<?php
require __DIR__.'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileType = 'Xlsx';
$inputFileName = 'D:\DOC\karyawan.xlsx';

$reader = IOFactory::createReader($inputFileType);
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($inputFileName);
$worksheet = $spreadsheet->getActiveSheet();

$rows = $worksheet->toArray(null, false, true, false);

if (count($rows) > 0) {
    echo "Header:\n";
    foreach ($rows[0] as $index => $col) {
        echo "[$index] => $col\n";
    }
}
