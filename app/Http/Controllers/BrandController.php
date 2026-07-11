<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BrandExport;
use App\Imports\BrandImport;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::query();
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }
        $brands = $query->orderBy('name')->paginate(10)->appends($request->all());
        return view('master.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('master.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'description' => 'nullable|string',
        ]);

        Brand::create($request->all());
        return redirect()->route('brands.index')->with('success', __('messages.created_success'));
    }

    public function edit(Brand $brand)
    {
        return view('master.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
        ]);

        $brand->update($request->all());
        return redirect()->route('brands.index')->with('success', __('messages.updated_success'));
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('brands.index')->with('success', __('messages.deleted_success'));
    }

    public function exportExcel()
    {
        return Excel::download(new BrandExport, 'brands.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new BrandImport, $request->file('file'));
            return redirect()->route('brands.index')->with('success', 'Data imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('brands.index')->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }
}
