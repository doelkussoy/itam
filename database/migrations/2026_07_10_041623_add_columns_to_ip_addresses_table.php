<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ip_addresses', function (Blueprint $table) {
            $table->foreignId('vlan_id')->nullable()->constrained('vlans')->onDelete('set null')->after('employee_id');
            $table->string('gateway')->nullable()->after('vlan_id');
            $table->string('dns')->nullable()->after('gateway');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ip_addresses', function (Blueprint $table) {
            $table->dropForeign(['vlan_id']);
            $table->dropColumn(['vlan_id', 'gateway', 'dns']);
        });
    }
};
