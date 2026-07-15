<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

DB::beginTransaction();
try {
    // 1. Delete all existing positions
    Employee::query()->update(['position_id' => null]);
    Position::query()->forceDelete();
    echo "Deleted old positions and unset employee position_ids.\n";

    // 2. Fetch all current departments
    $departments = Department::all();
    $deptToPosMap = [];

    // 3. Create positions with the same names
    foreach ($departments as $dept) {
        $pos = Position::create(['name' => $dept->name]);
        $deptToPosMap[$dept->id] = $pos->id;
    }
    echo "Migrated " . count($departments) . " departments to positions.\n";

    // 4. Update employees
    $updatedCount = 0;
    $employees = Employee::whereNotNull('department_id')->get();
    foreach ($employees as $emp) {
        if (isset($deptToPosMap[$emp->department_id])) {
            $emp->update([
                'position_id' => $deptToPosMap[$emp->department_id],
                'department_id' => null
            ]);
            $updatedCount++;
        }
    }
    echo "Updated $updatedCount employees.\n";

    // 5. Empty departments table
    Department::query()->forceDelete();
    echo "Emptied departments table.\n";

    DB::commit();
    echo "Success!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
