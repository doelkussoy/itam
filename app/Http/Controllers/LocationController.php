<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LocationExport;
use App\Imports\LocationImport;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $query = Location::query();
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }
        $locations = $query->orderBy('name')->paginate(10)->appends($request->all());
        return view('master.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('master.locations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:locations',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Location::create($request->all());
        return redirect()->route('locations.index', request()->query())->with('success', __('messages.created_success'));
    }

    public function edit(Location $location)
    {
        return view('master.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,' . $location->id,
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $location->update($request->all());
        return redirect()->route('locations.index', request()->query())->with('success', __('messages.updated_success'));
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('locations.index', request()->query())->with('success', __('messages.deleted_success'));
    }

    public function exportExcel()
    {
        return Excel::download(new LocationExport, 'locations.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new LocationImport, $request->file('file'));
            return redirect()->route('locations.index', request()->query())->with('success', 'Data imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('locations.index', request()->query())->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }
}
