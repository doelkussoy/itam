<?php

namespace App\Http\Controllers;

use App\Models\Vlan;
use Illuminate\Http\Request;

class VlanController extends Controller
{
    public function index(Request $request)
    {
        $query = Vlan::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('vlan_number', 'like', "%$search%")
                  ->orWhere('subnet', 'like', "%$search%");
            });
        }

        $vlans = $query->orderBy('vlan_number')->paginate(10)->appends($request->all());
        return view('vlans.index', compact('vlans'));
    }

    public function create()
    {
        return view('vlans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'vlan_number' => 'required|integer|unique:vlans,vlan_number',
            'name' => 'required|string|max:255',
            'subnet' => 'nullable|string|max:255',
            'gateway' => 'nullable|ip',
            'status' => 'required|in:Active,Inactive',
            'notes' => 'nullable|string'
        ]);

        try {
            Vlan::create($request->all());
            return redirect()->route('vlans.index')->with('success', __('messages.created_success'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create VLAN: ' . $e->getMessage());
        }
    }

    public function edit(Vlan $vlan)
    {
        return view('vlans.edit', compact('vlan'));
    }

    public function update(Request $request, Vlan $vlan)
    {
        $request->validate([
            'vlan_number' => 'required|integer|unique:vlans,vlan_number,' . $vlan->id,
            'name' => 'required|string|max:255',
            'subnet' => 'nullable|string|max:255',
            'gateway' => 'nullable|ip',
            'status' => 'required|in:Active,Inactive',
            'notes' => 'nullable|string'
        ]);

        try {
            $vlan->update($request->all());
            return redirect()->route('vlans.index')->with('success', __('messages.updated_success'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update VLAN: ' . $e->getMessage());
        }
    }

    public function destroy(Vlan $vlan)
    {
        $vlan->delete();
        return redirect()->route('vlans.index')->with('success', __('messages.deleted_success'));
    }
}
