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
        $tables = [
            'assets', 'employees', 'asset_assignments', 'maintenances', 
            'tickets', 'ip_addresses', 'departments', 'positions', 
            'categories', 'brands', 'locations', 'vendors'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'assets', 'employees', 'asset_assignments', 'maintenances', 
            'tickets', 'ip_addresses', 'departments', 'positions', 
            'categories', 'brands', 'locations', 'vendors'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
