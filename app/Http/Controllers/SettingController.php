<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Group settings by their 'group' field
        $settings = Setting::all()->groupBy('group');
        return view('settings.index', compact('settings'));
    }

    public function updateAll(Request $request)
    {
        $settings = $request->except(['_token', '_method']);
        
        foreach ($settings as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }
        
        return redirect()->route('settings.index', request()->query())->with('success', 'Settings updated successfully.');
    }
}
