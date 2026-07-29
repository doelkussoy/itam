<?php

namespace App\Console\Commands;

use App\Models\IpAddress;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AgentSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ips:agent-sync {--server= : URL Server Hosting (contoh: https://itam.domain.com)} {--interval=5 : Interval detik antar pinger}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Local Office Agent to ping local LAN IPs (192.168.x.x) and sync status to cloud hosting';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serverUrl = rtrim($this->option('server') ?: config('app.url'), '/');
        $interval = (int) $this->option('interval');

        $this->info("Starting ITAM Local Agent Sync...");
        $this->info("Target Server: {$serverUrl}");
        $this->info("Interval: {$interval}s");

        while (true) {
            // Get list of IPs from local DB or Server API
            $ips = IpAddress::all();
            if ($ips->isEmpty()) {
                sleep($interval);
                continue;
            }

            $statuses = [];
            $hasFping = ! stristr(PHP_OS, 'win') && ! empty(shell_exec('which fping 2>/dev/null'));

            if ($hasFping) {
                $ipList = $ips->pluck('ip_address')->unique()->toArray();
                $tempFile = storage_path('app/agent_ip_list.txt');
                file_put_contents($tempFile, implode("\n", $ipList));

                $fpingOutput = shell_exec('fping -a -t 300 < '.escapeshellarg($tempFile).' 2>&1');
                $aliveIps = array_filter(array_map('trim', explode("\n", $fpingOutput ?? '')));
                $aliveMap = array_flip($aliveIps);
                @unlink($tempFile);

                foreach ($ips as $ip) {
                    $isOnline = isset($aliveMap[$ip->ip_address]);
                    $statuses[] = [
                        'id' => $ip->id,
                        'ip' => $ip->ip_address,
                        'online' => $isOnline,
                    ];
                }
            } else {
                foreach ($ips as $ip) {
                    $ipAddress = $ip->ip_address;
                    $str = PHP_OS;
                    $command = stristr($str, 'win')
                        ? 'ping -n 1 -w 500 '.escapeshellarg($ipAddress)
                        : 'ping -c 1 -W 1 '.escapeshellarg($ipAddress);

                    exec($command, $outcome, $status);
                    $statuses[] = [
                        'id' => $ip->id,
                        'ip' => $ipAddress,
                        'online' => ($status === 0),
                    ];
                }
            }

            // Sync to local DB directly if running on local DB, or push via HTTP API
            foreach ($statuses as $st) {
                IpAddress::where('id', $st['id'])->update([
                    'is_online' => $st['online'],
                    'last_ping_at' => now(),
                ]);
            }

            // Push to remote server if server option is specified and different from local
            if ($this->option('server')) {
                try {
                    Http::post("{$serverUrl}/api/ips/agent-sync", [
                        'statuses' => $statuses,
                    ]);
                    $this->info("Synced ".count($statuses)." IPs status to {$serverUrl}");
                } catch (\Throwable $e) {
                    $this->error("Sync error: ".$e->getMessage());
                }
            } else {
                $this->info("Updated ".count($statuses)." local LAN IPs status");
            }

            sleep($interval);
        }

        return 0;
    }
}
