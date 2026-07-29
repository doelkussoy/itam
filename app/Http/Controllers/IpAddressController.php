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
        try {
            $result = $this->pingSingleIp($ip->ip_address, $ip);
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
        } catch (\Throwable $e) {
            return response()->json([
                'ip' => $ip->ip_address,
                'online' => false,
                'status' => 'Offline',
                'last_ping_at' => 'Error',
                'output' => 'Ping error: '.$e->getMessage(),
            ], 200);
        }
    }

    public function pingBatch(Request $request)
    {
        try {
            $ipIds = $request->input('ip_ids', []);
            if (empty($ipIds)) {
                return response()->json(['success' => false, 'results' => []]);
            }

            $ips = IpAddress::whereIn('id', $ipIds)->get();
            $results = [];

            foreach ($ips as $ip) {
                $result = $this->pingSingleIp($ip->ip_address, $ip);
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
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    private function pingSingleIp(string $ipAddress, ?IpAddress $ipModel = null): array
    {
        $outcome = [];
        $online = false;

        try {
            $disabledFunctions = array_map('trim', explode(',', strtolower(ini_get('disable_functions') ?: '')));
            $canExec = function_exists('exec') && ! in_array('exec', $disabledFunctions);

            if ($canExec) {
                if (stristr(PHP_OS, 'win')) {
                    $command = 'ping -n 1 -w 1000 '.escapeshellarg($ipAddress);
                    @exec($command, $outcome, $status);
                    if ($status === 0) {
                        $online = true;
                    }
                } else {
                    // 1. Try Linux standard ping with -W 2 (timeout 2s)
                    $command1 = 'ping -c 1 -W 2 '.escapeshellarg($ipAddress).' 2>&1';
                    @exec($command1, $outcome, $status);
                    if ($status === 0) {
                        $online = true;
                    } else {
                        // 2. Try ping with -w 2 (deadline 2s)
                        $command2 = 'ping -c 1 -w 2 '.escapeshellarg($ipAddress).' 2>&1';
                        @exec($command2, $outcome2, $status2);
                        if ($status2 === 0) {
                            $online = true;
                        } else {
                            // 3. Try arping (Layer 2 ARP ping - bypasses Windows Firewall!)
                            $arpCmd = 'arping -c 1 -w 1 '.escapeshellarg($ipAddress).' 2>&1';
                            @exec($arpCmd, $arpOutcome, $arpStatus);
                            if ($arpStatus === 0) {
                                $online = true;
                                $outcome[] = 'Reachable via arping';
                            } else {
                                // 4. Try fping fallback
                                $fpingCmd = 'fping -c 1 -t 300 '.escapeshellarg($ipAddress).' 2>&1';
                                @exec($fpingCmd, $fpingOutcome, $fpingStatus);
                                if ($fpingStatus === 0) {
                                    $online = true;
                                    $outcome[] = 'Reachable via fping';
                                }
                            }
                        }
                    }
                }
            }

            // Fallback 1: Multi-port socket connection (TCP & NetBIOS ports for Windows PCs & CCTV)
            if (! $online) {
                $ports = [135, 139, 445, 80, 443, 22, 8080, 3389, 53, 8000, 8443, 21, 23, 161, 5000, 554, 8081];
                foreach ($ports as $port) {
                    $connection = @fsockopen($ipAddress, $port, $errno, $errstr, 0.3);
                    if (is_resource($connection)) {
                        fclose($connection);
                        $online = true;
                        $outcome[] = "Reachable via port $port";
                        break;
                    }
                }
            }

            // Fallback 2: Linux kernel ARP cache (/proc/net/arp) for LAN IPs
            if (! $online && file_exists('/proc/net/arp')) {
                $arpData = @file_get_contents('/proc/net/arp');
                if ($arpData && preg_match('/^'.preg_quote($ipAddress, '/').'\s+\S+\s+\S+\s+([0-9a-fA-F:]{17})/m', $arpData, $m)) {
                    if (strtolower($m[1]) !== '00:00:00:00:00:00') {
                        $online = true;
                        $outcome[] = "Reachable via ARP cache (MAC: {$m[1]})";
                    }
                }
            }

            // Fallback 3: Agent Sync status if updated recently
            if (! $online && $ipModel && $ipModel->is_online && $this->isPrivateIp($ipAddress)) {
                if ($ipModel->last_ping_at && $ipModel->last_ping_at->gt(now()->subMinutes(30))) {
                    $online = true;
                    $outcome[] = 'Reachable via Agent Sync ('.$ipModel->last_ping_at->diffForHumans().')';
                }
            }
        } catch (\Throwable $e) {
            $online = false;
            $outcome[] = 'Ping note: '.$e->getMessage();
        }

        return [
            'online' => $online,
            'output' => implode("\n", $outcome),
        ];
    }

    private function isPrivateIp(string $ip): bool
    {
        return ! filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );
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

    public function agentSync(Request $request)
    {
        $statuses = $request->input('statuses', []);
        $updated = 0;

        foreach ($statuses as $item) {
            if (! empty($item['id'])) {
                IpAddress::where('id', $item['id'])->update([
                    'is_online' => (bool) $item['online'],
                    'last_ping_at' => now(),
                ]);
                $updated++;
            } elseif (! empty($item['ip'])) {
                IpAddress::where('ip_address', $item['ip'])->update([
                    'is_online' => (bool) $item['online'],
                    'last_ping_at' => now(),
                ]);
                $updated++;
            }
        }

        return response()->json([
            'success' => true,
            'updated' => $updated,
        ]);
    }
}
