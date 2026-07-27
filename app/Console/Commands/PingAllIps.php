<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PingAllIps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ips:ping-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping all IP addresses and notify Super Admins if offline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ips = \App\Models\IpAddress::with('asset')->get();
        $offlineIps = [];

        $this->info('Starting ping for ' . $ips->count() . ' IP addresses...');

        foreach ($ips as $ip) {
            $ipAddress = $ip->ip_address;
            $str = PHP_OS;
            
            // Limit ping to 1 packet, 1s timeout to make it fast
            if (stristr($str, 'win')) {
                $command = 'ping -n 1 -w 1000 ' . escapeshellarg($ipAddress);
            } else {
                $command = 'ping -c 1 -W 1 ' . escapeshellarg($ipAddress);
            }

            exec($command, $outcome, $status);

            if ($status !== 0) {
                // Offline
                $offlineIps[] = [
                    'ip_address' => $ipAddress,
                    'name' => $ip->asset ? $ip->asset->name : ($ip->notes ?? 'Unknown')
                ];
                $this->error("IP $ipAddress is OFFLINE");
            } else {
                $this->info("IP $ipAddress is ONLINE");
            }
        }

        if (count($offlineIps) > 0) {
            $this->info('Sending notification for ' . count($offlineIps) . ' offline IPs...');
            
            $superAdmins = \App\Models\User::role('Super Admin')->get();
            foreach ($superAdmins as $admin) {
                $admin->notify(new \App\Notifications\OfflineIpNotification($offlineIps));
            }
            
            $this->info('Notification sent.');
        } else {
            $this->info('All IPs are online. No notification sent.');
        }

        return 0;
    }
}
