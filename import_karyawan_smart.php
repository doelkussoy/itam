<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Employee;
use App\Models\Department;
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

$addedCount = 0;
$updatedCount = 0;

foreach ($rows as $index => $row) {
    if ($index === 0) continue; // Skip header
    
    // [0] => NIK
    // [1] => Employee Name
    // [2] => Posisition Name (Position is removed)
    // [3] => Department Name
    // [4] => Other Address
    // [5] => Phone Number Employee
    // [6] => Supervisor Name
    
    $nik = trim($row[0] ?? '');
    if (empty($nik)) continue; // skip empty NIK
    
    $name = trim($row[1] ?? '');
    $deptName = trim($row[3] ?? '');
    $phone = trim($row[5] ?? '');
    $supervisorName = trim($row[6] ?? '');
    
    // Find department
    $department = null;
    if (!empty($deptName)) {
        $department = Department::where('name', 'like', $deptName)->first();
    }
    
    // Find supervisor
    $supervisor = null;
    if (!empty($supervisorName)) {
        $supervisor = Employee::where('name', 'like', $supervisorName)->first();
    }
    
    $employee = Employee::withTrashed()->where('employee_id', $nik)->first();
    
    if ($employee) {
        if ($employee->trashed()) {
            $employee->restore();
        }
        // Update existing
        $employee->update([
            'name' => $name,
            'phone' => $phone,
            'department_id' => $department ? $department->id : null,
            'supervisor_id' => $supervisor ? $supervisor->id : null,
            // email is left untouched or null
        ]);
        $updatedCount++;
    } else {
        // Create new
        Employee::create([
            'employee_id' => $nik,
            'name' => $name,
            'email' => null, // No email
            'phone' => $phone,
            'department_id' => $department ? $department->id : null,
            'supervisor_id' => $supervisor ? $supervisor->id : null,
            'status' => 'Active',
        ]);
        $addedCount++;
    }
}

echo "Successfully added $addedCount employees and updated $updatedCount employees.\n";
