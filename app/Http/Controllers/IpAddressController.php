<?php

namespace App\Http\Controllers;

use App\Exports\IpAddressExport;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\IpAddress;
use App\Models\Vlan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

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
            'ip_address' => ['required', 'ip', Rule::unique('ip_addresses')->whereNull('deleted_at')],
            'mac_address' => 'nullable|string|max:17',
            'asset_id' => 'nullable|exists:assets,id',
            'employee_id' => 'nullable|exists:employees,id',
            'vlan_id' => 'nullable|exists:vlans,id',
            'gateway' => 'nullable|ip',
            'dns' => 'nullable|string',
            'status' => 'required|in:Available,Used,Reserved',
            'notes' => 'nullable|string',
        ]);

        IpAddress::create($request->all());

        return redirect()->route('ips.index', request()->query())->with('success', 'IP Address added successfully.');
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
            'ip_address' => ['required', 'ip', Rule::unique('ip_addresses')->ignore($ip->id)->whereNull('deleted_at')],
            'mac_address' => 'nullable|string|max:17',
            'asset_id' => 'nullable|exists:assets,id',
            'employee_id' => 'nullable|exists:employees,id',
            'vlan_id' => 'nullable|exists:vlans,id',
            'gateway' => 'nullable|ip',
            'dns' => 'nullable|string',
            'status' => 'required|in:Available,Used,Reserved',
            'notes' => 'nullable|string',
        ]);

        $ip->update($request->all());

        return redirect()->route('ips.index', request()->query())->with('success', 'IP Address updated successfully.');
    }

    public function destroy(IpAddress $ip)
    {
        $ip->delete();

        return redirect()->route('ips.index', request()->query())->with('success', 'IP Address deleted successfully.');
    }

    public function updateStatus(Request $request, IpAddress $ip)
    {
        $request->validate([
            'status' => 'required|in:Available,Used,Reserved',
        ]);

        $ip->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully.', 'status' => $ip->status]);
    }

    public function exportExcel()
    {
        return Excel::download(new IpAddressExport, 'ips.xlsx');
    }

    public function ping(Request $request, IpAddress $ip)
    {
        $result = $this->pingSingleIp($ip->ip_address);
        $online = $result['online'];

        $ip->update([
            'is_online' => $online,
            'last_ping_at' => now(),
        ]);

        return response()->json([
            'ip' => $ip->ip_address,
            'online' => $online,
            'status' => $online ? 'Online' : 'Offline',
            'last_ping_at' => $ip->last_ping_at ? $ip->last_ping_at->diffForHumans() : 'Just now',
            'output' => $result['output'],
        ]);
    }

    public function pingBatch(Request $request)
    {
        $ipIds = $request->input('ip_ids', []);
        if (empty($ipIds)) {
            return response()->json(['success' => false, 'results' => []]);
        }

        $ips = IpAddress::whereIn('id', $ipIds)->get();
        $results = [];

        foreach ($ips as $ip) {
            $result = $this->pingSingleIp($ip->ip_address);
            $online = $result['online'];

            $ip->update([
                'is_online' => $online,
                'last_ping_at' => now(),
            ]);

            $results[] = [
                'id' => $ip->id,
                'ip' => $ip->ip_address,
                'online' => $online,
                'status' => $online ? 'Online' : 'Offline',
                'last_ping_at' => 'Just now',
            ];
        }

        return response()->json([
            'success' => true,
            'results' => $results,
        ]);
    }

    private function pingSingleIp(string $ipAddress): array
    {
        $outcome = [];
        $online = false;

        try {
            $disabledFunctions = array_map('trim', explode(',', strtolower(ini_get('disable_functions') ?: '')));
            $canExec = function_exists('exec') && ! in_array('exec', $disabledFunctions);

            if ($canExec) {
                if (stristr(PHP_OS, 'win')) {
                    $command = 'ping -n 1 -w 1000 '.escapeshellarg($ipAddress);
                } else {
                    // Search for ping or fping binary path on Linux
                    $pingBin = trim(@shell_exec('which ping 2>/dev/null') ?: '');
                    if (empty($pingBin) || ! is_executable($pingBin)) {
                        $pingBin = file_exists('/bin/ping') ? '/bin/ping' : '/usr/bin/ping';
                    }
                    $command = escapeshellcmd($pingBin).' -c 1 -W 1 '.escapeshellarg($ipAddress);
                }

                @exec($command, $outcome, $status);
                if ($status === 0) {
                    $online = true;
                }
            }

            // Fallback to socket port connection if exec is disabled or ping ICMP is blocked by firewall
            if (! $online) {
                $ports = [80, 443, 22, 445, 8080, 139, 3389, 53];
                foreach ($ports as $port) {
                    $connection = @fsockopen($ipAddress, $port, $errno, $errstr, 0.4);
                    if (is_resource($connection)) {
                        fclose($connection);
                        $online = true;
                        $outcome[] = "Device reachable via port $port";
                        break;
                    }
                }
            }
        } catch (\Throwable $e) {
            $online = false;
            $outcome[] = 'Ping execution note: '.$e->getMessage();
        }

        return [
            'online' => $online,
            'output' => implode("\n", $outcome),
        ];
    }

    public function liveStatus(Request $request)
    {
        $ipIds = $request->input('ip_ids', []);
        $query = IpAddress::select('id', 'ip_address', 'is_online', 'last_ping_at');

        if (! empty($ipIds)) {
            $query->whereIn('id', $ipIds);
        }

        $ips = $query->get()->map(function ($ip) {
            return [
                'id' => $ip->id,
                'ip' => $ip->ip_address,
                'online' => $ip->is_online,
                'status' => $ip->is_online === true ? 'Online' : ($ip->is_online === false ? 'Offline' : 'Unchecked'),
                'last_ping_at' => $ip->last_ping_at ? $ip->last_ping_at->diffForHumans() : 'Never',
            ];
        });

        return response()->json([
            'success' => true,
            'ips' => $ips,
        ]);
    }
}
