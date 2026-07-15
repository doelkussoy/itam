<?php

namespace App\Http\Controllers;

use App\Models\PasswordVault;
use Illuminate\Http\Request;

class PasswordVaultController extends Controller
{
    public function index(Request $request)
    {
        $query = PasswordVault::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('device_name', 'like', "%$search%")
                  ->orWhere('username', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%");
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        $passwords = $query->orderBy('device_name')->paginate(10)->appends($request->all());
        $categories = PasswordVault::select('category')->distinct()->pluck('category');

        return view('password_vaults.index', compact('passwords', 'categories'));
    }

    public function create()
    {
        $categories = PasswordVault::select('category')->distinct()->pluck('category');
        return view('password_vaults.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'encrypted_password' => 'required|string',
            'category' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        try {
            PasswordVault::create([
                'device_name' => $request->device_name,
                'username' => $request->username,
                'encrypted_password' => $request->encrypted_password, // cast handles encryption
                'category' => $request->category,
                'notes' => $request->notes,
            ]);
            return redirect()->route('password_vaults.index')->with('success', __('messages.created_success'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to save password: ' . $e->getMessage());
        }
    }

    public function edit(PasswordVault $passwordVault)
    {
        $categories = PasswordVault::select('category')->distinct()->pluck('category');
        return view('password_vaults.edit', compact('passwordVault', 'categories'));
    }

    public function update(Request $request, PasswordVault $passwordVault)
    {
        $request->validate([
            'device_name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'encrypted_password' => 'required|string',
            'category' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        try {
            $passwordVault->update([
                'device_name' => $request->device_name,
                'username' => $request->username,
                'encrypted_password' => $request->encrypted_password,
                'category' => $request->category,
                'notes' => $request->notes,
            ]);
            return redirect()->route('password_vaults.index')->with('success', __('messages.updated_success'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update password: ' . $e->getMessage());
        }
    }

    public function destroy(PasswordVault $passwordVault)
    {
        $passwordVault->delete();
        return redirect()->route('password_vaults.index')->with('success', __('messages.deleted_success'));
    }
}
