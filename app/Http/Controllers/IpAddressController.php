<?php

namespace App\Http\Controllers;

use App\Models\IpAddress;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\Vlan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IpAddressExport;

class IpAddressController extends Controller
{
    public function index(Request $request)
    {
        $query = IpAddress::with(['asset', 'employee', 'vlan']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%$search%")
                    ->orWhere('notes', 'like', "%$search%")
                    ->orWhereHas('asset', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%$search%")
                            ->orWhere('asset_tag', 'like', "%$search%")
                            ->orWhereHas('category', function ($q3) use ($search) {
                                $q3->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('brand', function ($q3) use ($search) {
                                $q3->where('name', 'like', "%$search%");
                            });
                    })
                    ->orWhereHas('employee', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%$search%")
                            ->orWhere('employee_id', 'like', "%$search%");
                    });
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $ips = $query->orderByRaw('INET_ATON(ip_address)')->paginate(15)->appends($request->all());
        return view('ips.index', compact('ips'));
    }

    public function create()
    {
        $assets = Asset::orderBy('name')->get();
        $employees = Employee::orderBy('name')->get();
        $vlans = Vlan::orderBy('vlan_number')->get();
        return view('ips.create', compact('assets', 'employees', 'vlans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:ip_addresses,ip_address',
            'mac_address' => 'nullable|string|max:17',
            'asset_id' => 'nullable|exists:assets,id',
            'employee_id' => 'nullable|exists:employees,id',
            'vlan_id' => 'nullable|exists:vlans,id',
            'gateway' => 'nullable|ip',
            'dns' => 'nullable|string',
            'status' => 'required|in:Available,Used,Reserved',
            'notes' => 'nullable|string'
        ]);

        IpAddress::create($request->all());

        return redirect()->route('ips.index')->with('success', 'IP Address added successfully.');
    }

    public function edit(IpAddress $ip)
    {
        $assets = Asset::orderBy('name')->get();
        $employees = Employee::orderBy('name')->get();
        $vlans = Vlan::orderBy('vlan_number')->get();
        return view('ips.edit', compact('ip', 'assets', 'employees', 'vlans'));
    }

    public function update(Request $request, IpAddress $ip)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:ip_addresses,ip_address,' . $ip->id,
            'mac_address' => 'nullable|string|max:17',
            'asset_id' => 'nullable|exists:assets,id',
            'employee_id' => 'nullable|exists:employees,id',
            'vlan_id' => 'nullable|exists:vlans,id',
            'gateway' => 'nullable|ip',
            'dns' => 'nullable|string',
            'status' => 'required|in:Available,Used,Reserved',
            'notes' => 'nullable|string'
        ]);

        $ip->update($request->all());

        return redirect()->route('ips.index')->with('success', 'IP Address updated successfully.');
    }

    public function destroy(IpAddress $ip)
    {
        $ip->delete();
        return redirect()->route('ips.index')->with('success', 'IP Address deleted successfully.');
    }

    public function exportExcel()
    {
        return Excel::download(new IpAddressExport, 'ips.xlsx');
    }

    public function ping(Request $request, IpAddress $ip)
    {
        $ipAddress = $ip->ip_address;

        $str = PHP_OS;
        if (stristr($str, 'win')) {
            $command = 'ping -n 1 -w 1000 ' . escapeshellarg($ipAddress);
        } else {
            $command = 'ping -c 1 -W 1 ' . escapeshellarg($ipAddress);
        }

        exec($command, $outcome, $status);

        $online = ($status === 0);

        return response()->json([
            'ip' => $ipAddress,
            'online' => $online,
            'status' => $online ? 'Online' : 'Offline',
            'output' => implode("\n", $outcome)
        ]);
    }
}
