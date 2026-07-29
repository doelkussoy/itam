<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\IpAddress;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $ips = IpAddress::with('vlan')->get();
        foreach ($ips as $ip) {
            $targetGateway = null;
            if ($ip->vlan && $ip->vlan->vlan_number) {
                $targetGateway = "192.168.{$ip->vlan->vlan_number}.254";
            } elseif ($ip->ip_address && preg_match('/^(\d+\.\d+\.\d+)\.\d+$/', $ip->ip_address, $m)) {
                $targetGateway = "{$m[1]}.254";
            }

            if ($targetGateway && $ip->gateway !== $targetGateway) {
                $ip->update(['gateway' => $targetGateway]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
