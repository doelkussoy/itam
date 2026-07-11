<?php

namespace App\Http\Controllers;

use App\Models\AssetAssignment;
use App\Models\Asset;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AssetAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = AssetAssignment::with(['asset', 'employee']);
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('asset', function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('asset_tag', 'like', "%$search%");
            })->orWhereHas('employee', function($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }
        
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('assigned_date', $request->date);
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $assignments = $query->latest()->paginate(10)->appends($request->all());
        return view('assignments.index', compact('assignments'));
    }

    public function create()
    {
        // Only get assets that are Available
        $assets = Asset::where('status', 'Available')->orderBy('name')->get();
        $employees = Employee::orderBy('name')->get();
        return view('assignments.create', compact('assets', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'employee_id' => 'required|exists:employees,id',
            'assigned_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        // Check if asset is still available
        $asset = Asset::findOrFail($request->asset_id);
        if ($asset->status !== 'Available') {
            return back()->with('error', 'Asset is not available for assignment.')->withInput();
        }

        $assignment = AssetAssignment::create([
            'asset_id' => $request->asset_id,
            'employee_id' => $request->employee_id,
            'assigned_date' => $request->assigned_date,
            'status' => 'Assigned',
            'notes' => $request->notes
        ]);

        // Update asset status
        $asset->update(['status' => 'Assigned']);

        return redirect()->route('assignments.index')->with('success', 'Asset successfully assigned.');
    }

    public function edit(AssetAssignment $assignment)
    {
        $employees = Employee::orderBy('name')->get();
        return view('assignments.edit', compact('assignment', 'employees'));
    }

    public function update(Request $request, AssetAssignment $assignment)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'assigned_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $assignment->update([
            'employee_id' => $request->employee_id,
            'assigned_date' => $request->assigned_date,
            'notes' => $request->notes
        ]);

        return redirect()->route('assignments.index')->with('success', 'Assignment updated successfully.');
    }

    public function destroy(AssetAssignment $assignment)
    {
        // If it's still assigned, make the asset available again before deleting the record.
        if ($assignment->status === 'Assigned') {
            $assignment->asset->update(['status' => 'Available']);
        }
        $assignment->delete();
        return redirect()->route('assignments.index')->with('success', 'Assignment record deleted.');
    }

    public function returnAsset(Request $request, AssetAssignment $assignment)
    {
        $request->validate([
            'return_date' => 'required|date'
        ]);

        if ($assignment->status !== 'Assigned') {
            return back()->with('error', 'Asset is already returned.');
        }

        $assignment->update([
            'return_date' => $request->return_date,
            'status' => 'Returned',
            'notes' => $assignment->notes . "\nReturned on " . $request->return_date . ": " . $request->return_notes
        ]);

        $assignment->asset->update(['status' => 'Available']);

        return redirect()->route('assignments.index')->with('success', 'Asset successfully returned.');
    }
}
