<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DepartmentExport;
use App\Imports\DepartmentImport;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::query();
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }
        $departments = $query->orderBy('name')->paginate(10)->appends($request->all());
        return view('master.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('master.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
        ]);

        Department::create($request->all());
        return redirect()->route('departments.index', request()->query())->with('success', __('messages.created_success'));
    }

    public function edit(Department $department)
    {
        return view('master.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
        ]);

        $department->update($request->all());
        return redirect()->route('departments.index', request()->query())->with('success', __('messages.updated_success'));
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index', request()->query())->with('success', __('messages.deleted_success'));
    }

    public function exportExcel()
    {
        return Excel::download(new DepartmentExport, 'departments.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new DepartmentImport, $request->file('file'));
            return redirect()->route('departments.index', request()->query())->with('success', 'Data imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('departments.index', request()->query())->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }
}
