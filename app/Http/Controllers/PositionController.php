<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PositionExport;
use App\Imports\PositionImport;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $query = Position::with('department');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }
        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }

        $departments = Department::orderBy('name')->get();
        $positions = $query->orderBy('name')->paginate(10)->appends($request->all());
        return view('master.positions.index', compact('positions', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('master.positions.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Position::create($request->all());
        return redirect()->route('positions.index')->with('success', __('messages.created_success'));
    }

    public function edit(Position $position)
    {
        $departments = Department::all();
        return view('master.positions.edit', compact('position', 'departments'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $position->update($request->all());
        return redirect()->route('positions.index')->with('success', __('messages.updated_success'));
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('positions.index')->with('success', __('messages.deleted_success'));
    }

    public function exportExcel()
    {
        return Excel::download(new PositionExport, 'positions.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new PositionImport, $request->file('file'));
            return redirect()->route('positions.index')->with('success', 'Data imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('positions.index')->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }
}
