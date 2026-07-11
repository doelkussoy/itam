<?php

namespace App\Http\Controllers;

use App\Models\SoftwareLicense;
use App\Models\Employee;
use Illuminate\Http\Request;

class SoftwareLicenseController extends Controller
{
    public function index(Request $request)
    {
        $query = SoftwareLicense::with('pic');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('license_key', 'like', "%$search%");
            });
        }

        $software_licenses = $query->orderBy('name')->paginate(10)->appends($request->all());
        return view('software_licenses.index', compact('software_licenses'));
    }

    public function create()
    {
        $employees = Employee::orderBy('name')->get();
        return view('software_licenses.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'license_key' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'total_seats' => 'required|integer|min:1',
            'pic_id' => 'nullable|exists:employees,id',
            'notes' => 'nullable|string'
        ]);

        try {
            SoftwareLicense::create($request->all());
            return redirect()->route('software_licenses.index')->with('success', __('messages.created_success'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create Software License: ' . $e->getMessage());
        }
    }

    public function edit(SoftwareLicense $softwareLicense)
    {
        $employees = Employee::orderBy('name')->get();
        return view('software_licenses.edit', compact('softwareLicense', 'employees'));
    }

    public function update(Request $request, SoftwareLicense $softwareLicense)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'license_key' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'total_seats' => 'required|integer|min:1',
            'pic_id' => 'nullable|exists:employees,id',
            'notes' => 'nullable|string'
        ]);

        try {
            $softwareLicense->update($request->all());
            return redirect()->route('software_licenses.index')->with('success', __('messages.updated_success'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update Software License: ' . $e->getMessage());
        }
    }

    public function destroy(SoftwareLicense $softwareLicense)
    {
        $softwareLicense->delete();
        return redirect()->route('software_licenses.index')->with('success', __('messages.deleted_success'));
    }
}
