<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Location;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetsExport;
use App\Imports\AssetsImport;

class AssetController extends Controller
{
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            // Delete all existing assets and reset auto-increment to replace with imported data
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Asset::truncate();
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            Excel::import(new AssetsImport, $request->file('file'));
            return back()->with('success', 'Assets successfully imported and replaced!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error importing assets: ' . $e->getMessage());
        }
    }

    public function exportExcel()
    {
        return Excel::download(new AssetsExport, 'assets_' . date('Ymd_His') . '.xlsx');
    }

    public function generateTag(Request $request)
    {
        $categoryId = $request->category_id;
        if (!$categoryId) {
            return response()->json(['tag' => '']);
        }

        $category = Category::find($categoryId);
        if (!$category) {
            return response()->json(['tag' => '']);
        }

        // Generate prefix, e.g., "LAP" for Laptop, "PRI" for Printer
        $words = explode(' ', trim(str_replace('-', ' ', $category->name)));
        if (count($words) >= 2) {
            $prefix = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 2));
        } else {
            $prefix = strtoupper(substr($category->name, 0, 3));
        }

        $year = date('Y');
        
        $latestAsset = Asset::where('asset_tag', 'like', $prefix . '-' . $year . '-%')
            ->orderBy('asset_tag', 'desc')
            ->first();

        $sequence = 1;
        if ($latestAsset) {
            $parts = explode('-', $latestAsset->asset_tag);
            if (count($parts) === 3) {
                $sequence = (int)$parts[2] + 1;
            }
        }

        $tag = sprintf("%s-%s-%03d", $prefix, $year, $sequence);

        return response()->json(['tag' => $tag]);
    }

    public function index(Request $request)
    {
        $query = Asset::with(['category', 'brand', 'location']);

        if ($request->has('category') && $request->category != '') {
            $category = Category::where('name', $request->category)->first();
            if ($category) {
                $request->merge(['category_id' => $category->id]);
            }
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('asset_tag', 'like', "%$search%")
                  ->orWhere('serial_number', 'like', "%$search%")
                  ->orWhere('delivery_order_number', 'like', "%$search%");
            });
        }
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }
        if ($request->has('brand_id') && $request->brand_id != '') {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->has('location_id') && $request->location_id != '') {
            $query->where('location_id', $request->location_id);
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        $assets = $query->orderBy('name')->paginate(10)->appends($request->all());
        return view('assets.index', compact('assets', 'categories', 'brands', 'locations'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $locations = Location::all();
        return view('assets.create', compact('categories', 'brands', 'locations'));
    }

    public function store(\App\Http\Requests\StoreAssetRequest $request)
    {
        try {
            $quantity = $request->input('quantity', 1);
            $baseTag = $request->asset_tag;
            $baseSn = $request->serial_number;

            // Extract numeric part from the end of the asset tag
            $prefix = $baseTag;
            $number = '';
            if (preg_match('/^(.*?)(\d+)$/', $baseTag, $matches)) {
                $prefix = $matches[1];
                $number = $matches[2];
            }

            for ($i = 0; $i < $quantity; $i++) {
                $currentTag = $baseTag;
                $currentSn = $baseSn;

                if ($i > 0) {
                    if ($number !== '') {
                        // Increment the numeric part, preserving leading zeros
                        $length = strlen($number);
                        $newNumber = str_pad((int)$number + $i, $length, '0', STR_PAD_LEFT);
                        $currentTag = $prefix . $newNumber;
                    } else {
                        // No numeric part, just append
                        $currentTag = $baseTag . '-' . ($i + 1);
                    }

                    if ($baseSn) {
                        $currentSn = $baseSn . '-' . ($i + 1);
                    }
                }

                // Fallback for duplicates in loop
                if ($i > 0 && Asset::where('asset_tag', $currentTag)->exists()) {
                    $currentTag = $currentTag . '-' . uniqid();
                }

                $asset = Asset::create([
                    'asset_tag' => $currentTag,
                    'name' => $request->name,
                    'serial_number' => $currentSn,
                    'category_id' => $request->category_id,
                    'brand_id' => $request->brand_id,
                    'location_id' => $request->location_id,
                    'date_received' => $request->date_received,
                    'delivery_order_number' => $request->delivery_order_number,
                    'warranty_months' => $request->warranty_months ?? 0,
                    'status' => $request->status,
                    'notes' => $request->notes,
                ]);

                if ($quantity == 1) {
                    $this->saveSpecifications($asset, $request);
                }
            }

            return redirect()->route('assets.index')->with('success', $quantity > 1 ? $quantity . ' Assets successfully created.' : __('messages.created_success'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create asset: ' . $e->getMessage());
        }
    }

    public function show(Asset $asset)
    {
        $asset->load(['category', 'brand', 'location', 'computer', 'printer', 'monitor', 'networkDetail', 'cctv', 'assignments.employee', 'maintenances', 'ipAddresses']);
        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $asset->load(['computer', 'printer', 'monitor', 'networkDetail', 'cctv']);
        $categories = Category::all();
        $brands = Brand::all();
        $locations = Location::all();
        return view('assets.edit', compact('asset', 'categories', 'brands', 'locations'));
    }

    public function update(\App\Http\Requests\UpdateAssetRequest $request, Asset $asset)
    {
        try {
            $data = $request->all();
            $data['warranty_months'] = $request->warranty_months ?? 0;
            $asset->update($data);
            $this->saveSpecifications($asset, $request);
            return redirect()->route('assets.index')->with('success', __('messages.updated_success'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update asset: ' . $e->getMessage());
        }
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', __('messages.deleted_success'));
    }

    private function saveSpecifications(Asset $asset, Request $request)
    {
        $category = $asset->category;
        if (!$category) return;

        $catName = strtolower($category->name);

        if (in_array($catName, ['komputer', 'laptop', 'mini pc', 'thin client', 'server'])) {
            $asset->computer()->updateOrCreate([], [
                'cpu' => $request->cpu,
                'ram' => $request->ram,
                'ssd' => $request->ssd,
                'hdd' => $request->hdd,
                'gpu' => $request->gpu,
                'os' => $request->os,
                'office' => $request->office,
            ]);
        } elseif ($catName == 'printer') {
            $asset->printer()->updateOrCreate([], [
                'type' => $request->printer_type ?? 'Laser',
                'connection_type' => $request->connection_type ?? 'USB',
                'has_scanner' => $request->has('has_scanner') ? 1 : 0,
                'counter_print' => $request->counter_print ?? 0,
                'toner_status' => $request->toner_status,
                'drum_status' => $request->drum_status,
            ]);
        } elseif ($catName == 'monitor') {
            $asset->monitor()->updateOrCreate([], [
                'size' => $request->monitor_size,
            ]);
        } elseif (in_array($catName, ['switch', 'router', 'access point', 'firewall', 'nas', 'ip phone'])) {
            $asset->networkDetail()->updateOrCreate([], [
                'firmware' => $request->network_firmware,
                'port_count' => $request->port_count,
                'active_ports' => $request->active_ports,
                'backup_config_path' => $request->backup_config_path,
                'ssid' => $request->ssid,
                'wifi_password' => $request->wifi_password,
                'controller' => $request->controller,
            ]);
        } elseif (in_array($catName, ['cctv', 'camera'])) {
            $asset->cctv()->updateOrCreate([], [
                'nvr_channel' => $request->nvr_channel,
                'firmware' => $request->cctv_firmware,
                'username' => $request->cctv_username,
                'password' => $request->cctv_password,
            ]);
        }
    }
}
