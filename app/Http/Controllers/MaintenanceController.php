<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Asset;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MaintenanceExport;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Maintenance::with('asset');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('asset', function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('asset_tag', 'like', "%$search%");
            })->orWhere('description', 'like', "%$search%");
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $maintenances = $query->latest()->paginate(10)->appends($request->all());
        return view('maintenances.index', compact('maintenances'));
    }

    public function create()
    {
        $assets = Asset::orderBy('name')->get();
        return view('maintenances.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'type' => 'required|in:Routine,Repair,Upgrade',
            'description' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'start_date' => 'required|date'
        ]);

        $maintenance = Maintenance::create([
            'asset_id' => $request->asset_id,
            'type' => $request->type,
            'description' => $request->description,
            'cost' => $request->cost,
            'start_date' => $request->start_date,
            'status' => 'Ongoing'
        ]);

        // Update asset status
        $maintenance->asset->update(['status' => 'Maintenance']);

        return redirect()->route('maintenances.index')->with('success', 'Maintenance record created successfully.');
    }

    public function edit(Maintenance $maintenance)
    {
        $assets = Asset::orderBy('name')->get();
        return view('maintenances.edit', compact('maintenance', 'assets'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'type' => 'required|in:Routine,Repair,Upgrade',
            'description' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'status' => 'required|in:Ongoing,Completed,Cancelled'
        ]);

        $maintenance->update([
            'type' => $request->type,
            'description' => $request->description,
            'cost' => $request->cost,
            'start_date' => $request->start_date,
            'status' => $request->status
        ]);

        // If completed or cancelled, make asset available again
        // (Assuming it was unavailable during maintenance)
        if (in_array($request->status, ['Completed', 'Cancelled'])) {
            $maintenance->asset->update(['status' => 'Available']);
        } else {
            $maintenance->asset->update(['status' => 'Maintenance']);
        }

        return redirect()->route('maintenances.index')->with('success', 'Maintenance updated successfully.');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenances.index')->with('success', 'Maintenance record deleted successfully.');
    }

    public function exportExcel()
    {
        return Excel::download(new MaintenanceExport, 'maintenances.xlsx');
    }

    public function complete(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'end_date' => 'required|date'
        ]);

        if ($maintenance->status !== 'Ongoing') {
            return back()->with('error', 'Maintenance is already completed or cancelled.');
        }

        $maintenance->update([
            'end_date' => $request->end_date,
            'status' => 'Completed'
        ]);

        $maintenance->asset->update(['status' => 'Available']);

        return redirect()->route('maintenances.index')->with('success', 'Maintenance marked as completed.');
    }
}
