<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Employee;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $total_assets = Asset::count();
        $total_employees = Employee::count();
        $active_assets = Asset::whereIn('status', ['Available', 'Assigned'])->count();
        $maintenance_assets = Asset::where('status', 'Maintenance')->count();
        
        $open_tickets = \App\Models\Ticket::whereIn('status', ['Open', 'In Progress'])->count();
        $used_ips = \App\Models\IpAddress::where('status', 'Used')->count();
        $assigned_assets = Asset::where('status', 'Assigned')->count();

        // Specific Category Counts for Dashboard Widgets
        $accessories_count = Asset::whereHas('category', function($q) {
            $q->where('name', 'Accessories');
        })->count();
        $cctv_count = Asset::whereHas('category', function($q) {
            $q->where('name', 'CCTV');
        })->count();
        $computer_count = Asset::whereHas('category', function($q) {
            $q->where('name', 'Computer');
        })->count();
        $network_count = Asset::whereHas('category', function($q) {
            $q->where('name', 'Network');
        })->count();
        $storage_count = Asset::whereHas('category', function($q) {
            $q->where('name', 'Storage');
        })->count();
        $other_it_asset_count = Asset::whereHas('category', function($q) {
            $q->where('name', 'Other IT Asset');
        })->count();

        // Expiring Warranties (Disabled)
        $expiring_warranty_count = 0;

        // Charts data
        // 1. Assets by Age
        // We bucket them based on date_received
        $now = Carbon::now();
        $age_buckets = [
            '0_3' => 0,
            '4_6' => 0,
            '7_10' => 0,
            'over_10' => 0
        ];
        Asset::all()->each(function($asset) use ($now, &$age_buckets) {
            $date = $asset->date_received ? Carbon::parse($asset->date_received) : $asset->created_at;
            $years = $date->diffInYears($now);
            if ($years <= 3) $age_buckets['0_3']++;
            elseif ($years <= 6) $age_buckets['4_6']++;
            elseif ($years <= 10) $age_buckets['7_10']++;
            else $age_buckets['over_10']++;
        });

        // 2. Assets by Location
        $locations_data = Asset::leftJoin('locations', 'assets.location_id', '=', 'locations.id')
            ->select(\DB::raw('COALESCE(locations.name, "Unassigned") as name'), \DB::raw('count(*) as count'))
            ->groupBy(\DB::raw('COALESCE(locations.name, "Unassigned")'))
            ->get();

        // 3. IP Address Distribution
        $ip_data = \App\Models\IpAddress::select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // 4. Ticket Status Distribution
        $ticket_data = \App\Models\Ticket::select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $activities = collect();
        $assignments = \App\Models\AssetAssignment::with(['asset', 'employee'])->latest()->take(5)->get();
        foreach($assignments as $a) {
            $activities->push((object)[
                'date' => $a->created_at,
                'operator' => 'Admin',
                'status_event' => $a->status == 'Assigned' ? __('messages.assigned') : __('messages.returned'),
                'badge' => $a->status == 'Assigned' ? 'success' : 'info',
                'asset' => $a->asset ? $a->asset->name . ' (' . $a->asset->asset_tag . ')' : 'N/A'
            ]);
        }
        $maintenances = \App\Models\Maintenance::with('asset')->latest()->take(5)->get();
        foreach($maintenances as $m) {
            $activities->push((object)[
                'date' => $m->created_at,
                'operator' => 'IT Support', 
                'status_event' => __('messages.maintenance'),
                'badge' => 'warning',
                'asset' => $m->asset ? $m->asset->name . ' (' . $m->asset->asset_tag . ')' : 'N/A'
            ]);
        }
        $recent_activities = $activities->sortByDesc('date')->take(5);

        return view('dashboard', compact(
            'total_assets',
            'total_employees',
            'active_assets',
            'maintenance_assets',
            'open_tickets',
            'used_ips',
            'assigned_assets',
            'recent_activities',
            'accessories_count',
            'cctv_count',
            'computer_count',
            'network_count',
            'storage_count',
            'other_it_asset_count',
            'expiring_warranty_count',
            'age_buckets', 
            'locations_data', 
            'ip_data', 
            'ticket_data'
        ));
    }

    public function activities()
    {
        $activities = collect();
        $assignments = \App\Models\AssetAssignment::with(['asset', 'employee'])->latest()->take(5)->get();
        foreach($assignments as $a) {
            $activities->push((object)[
                'date' => $a->created_at,
                'operator' => 'Admin',
                'status_event' => $a->status == 'Assigned' ? __('messages.assigned') : __('messages.returned'),
                'badge' => $a->status == 'Assigned' ? 'success' : 'info',
                'asset' => $a->asset ? $a->asset->name . ' (' . $a->asset->asset_tag . ')' : 'N/A'
            ]);
        }
        $maintenances = \App\Models\Maintenance::with('asset')->latest()->take(5)->get();
        foreach($maintenances as $m) {
            $activities->push((object)[
                'date' => $m->created_at,
                'operator' => 'IT Support', 
                'status_event' => __('messages.maintenance'),
                'badge' => 'warning',
                'asset' => $m->asset ? $m->asset->name . ' (' . $m->asset->asset_tag . ')' : 'N/A'
            ]);
        }
        $recent_activities = $activities->sortByDesc('date')->take(5);

        $formatted = $recent_activities->map(function($act) {
            $isToday = $act->date->isToday();
            $isYesterday = $act->date->isYesterday();
            if ($isToday) {
                $timeStr = __('messages.today') . ', ' . $act->date->format('H:i');
            } elseif ($isYesterday) {
                $timeStr = __('messages.yesterday') . ', ' . $act->date->format('H:i');
            } else {
                $timeStr = $act->date->format('d M, H:i');
            }

            return [
                'timestamp' => $timeStr,
                'operator' => $act->operator,
                'status_event' => $act->status_event,
                'badge' => $act->badge,
                'asset' => $act->asset,
            ];
        });

        return response()->json($formatted->values());
    }
}
