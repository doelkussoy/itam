<?php

namespace App\Console\Commands;

use App\Models\IpAddress;
use App\Models\User;
use App\Notifications\OfflineIpNotification;
use Illuminate\Console\Command;

class PingDaemon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ips:ping-daemon {--interval=3 : Seconds between ping cycles}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Continuous zero-delay background ping daemon for instant IP offline detection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $interval = (int) $this->option('interval');
        $this->info("Starting instant real-time IP daemon (interval: {$interval}s)...");

        // Keep track of previous online states to detect changes
        $previousStates = IpAddress::pluck('is_online', 'id')->toArray();

        while (true) {
            $ips = IpAddress::with('asset')->get();
            if ($ips->isEmpty()) {
                sleep($interval);
                continue;
            }

            $offlineIps = [];
            $hasFping = ! stristr(PHP_OS, 'win') && ! empty(shell_exec('which fping 2>/dev/null'));

            if ($hasFping) {
                // Ultra-fast parallel fping on Linux aaPanel
                $ipList = $ips->pluck('ip_address')->unique()->toArray();
                $tempFile = storage_path('app/ip_daemon_list.txt');
                file_put_contents($tempFile, implode("\n", $ipList));

                $fpingOutput = shell_exec('fping -a -t 200 < '.escapeshellarg($tempFile).' 2>&1');
                $aliveIps = array_filter(array_map('trim', explode("\n", $fpingOutput ?? '')));
                $aliveMap = array_flip($aliveIps);
                @unlink($tempFile);

                $newlyOffline = [];

                foreach ($ips as $ip) {
                    $isOnline = isset($aliveMap[$ip->ip_address]);
                    $oldOnline = $previousStates[$ip->id] ?? null;

                    // Update DB if state changed or unchecked
                    if ($oldOnline !== $isOnline || $ip->is_online === null) {
                        $ip->update([
                            'is_online' => $isOnline,
                            'last_ping_at' => now(),
                        ]);
                        $previousStates[$ip->id] = $isOnline;

                        if ($isOnline === false) {
                            $newlyOffline[] = [
                                'ip_address' => $ip->ip_address,
                                'name' => $ip->asset ? $ip->asset->name : ($ip->notes ?? 'Unknown'),
                            ];
                        }
                    }
                }

                // If any IP just went offline in this cycle, send instant notification
                if (! empty($newlyOffline)) {
                    $this->error('Instant Alert: '.count($newlyOffline).' IP(s) just went offline!');
                    $superAdmins = User::role('Super Admin')->get();
                    foreach ($superAdmins as $admin) {
                        $admin->notify(new OfflineIpNotification($newlyOffline));
                    }
                }
            } else {
                // Windows / Fallback sequential fast ping
                foreach ($ips as $ip) {
                    $ipAddress = $ip->ip_address;
                    $str = PHP_OS;
                    $command = stristr($str, 'win')
                        ? 'ping -n 1 -w 500 '.escapeshellarg($ipAddress)
                        : 'ping -c 1 -W 1 '.escapeshellarg($ipAddress);

                    exec($command, $outcome, $status);
                    $isOnline = ($status === 0);
                    $oldOnline = $previousStates[$ip->id] ?? null;

                    if ($oldOnline !== $isOnline || $ip->is_online === null) {
                        $ip->update([
                            'is_online' => $isOnline,
                            'last_ping_at' => now(),
                        ]);
                        $previousStates[$ip->id] = $isOnline;
                    }
                }
            }

            sleep($interval);
        }

        return 0;
    }
}
