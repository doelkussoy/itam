<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Imports\EmployeeImport;
use App\Exports\EmployeeExport;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('employee_id', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('anydesk_id', 'like', "%$search%")
                  ->orWhere('login_username', 'like', "%$search%");
            });
        }
        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }
        if ($request->has('supervisor_id') && $request->supervisor_id != '') {
            $query->where('supervisor_id', $request->supervisor_id);
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $departments = Department::orderBy('name')->get();
        // Get only employees who are supervisors
        $supervisors = Employee::whereIn('id', Employee::whereNotNull('supervisor_id')->pluck('supervisor_id')->unique())->orderBy('name')->get();
        
        $employees = $query->orderBy('name')->paginate(10)->appends($request->all());
        
        return view('employee.index', compact('employees', 'departments', 'supervisors'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $supervisors = Employee::orderBy('name')->get();
        return view('employee.create', compact('departments', 'locations', 'supervisors'));
    }

    public function store(\App\Http\Requests\StoreEmployeeRequest $request)
    {
        try {
            Employee::create($request->all());
            return redirect()->route('employees.index')->with('success', __('messages.created_success'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create employee: ' . $e->getMessage());
        }
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'department',
            'location',
            'supervisor',
            'assignments.asset.category',
            'assignments.asset.brand',
            'assignments.asset.location',
            'ipAddresses',
            'softwareLicenses'
        ]);
        return view('employee.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $supervisors = Employee::where('id', '!=', $employee->id)->orderBy('name')->get();
        return view('employee.edit', compact('employee', 'departments', 'locations', 'supervisors'));
    }

    public function update(\App\Http\Requests\UpdateEmployeeRequest $request, Employee $employee)
    {
        try {
            $employee->update($request->all());
            return redirect()->route('employees.index')->with('success', __('messages.updated_success'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update employee: ' . $e->getMessage());
        }
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', __('messages.deleted_success'));
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new EmployeeImport, $request->file('file'));
            return redirect()->route('employees.index')->with('success', 'Employees imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->with('error', 'Failed to import data: ' . $e->getMessage());
        }
    }

    public function exportExcel()
    {
        return Excel::download(new EmployeeExport, 'employees.xlsx');
    }
}
