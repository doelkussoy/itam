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
        // Computers / Laptops Detail Table
        Schema::create('computers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->string('cpu')->nullable();
            $table->integer('ram')->nullable()->comment('RAM in GB');
            $table->integer('ssd')->nullable()->comment('SSD in GB');
            $table->integer('hdd')->nullable()->comment('HDD in GB');
            $table->string('gpu')->nullable();
            $table->string('os')->nullable()->comment('Operating System');
            $table->string('office')->nullable()->comment('Office Software');
            $table->timestamps();
        });

        // Printers Detail Table
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->enum('type', ['Laser', 'Inkjet', 'Thermal', 'DotMatrix'])->default('Laser');
            $table->enum('connection_type', ['Network', 'USB'])->default('USB');
            $table->boolean('has_scanner')->default(false);
            $table->integer('counter_print')->default(0);
            $table->string('toner_status')->nullable();
            $table->string('drum_status')->nullable();
            $table->timestamps();
        });

        // Monitors Detail Table
        Schema::create('monitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->decimal('size', 4, 1)->nullable()->comment('Size in inches');
            $table->timestamps();
        });

        // Network Details (Switch, Router, Access Point) Table
        Schema::create('network_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->string('firmware')->nullable();
            $table->integer('port_count')->nullable();
            $table->integer('active_ports')->nullable();
            $table->string('backup_config_path')->nullable();
            $table->string('ssid')->nullable();
            $table->string('wifi_password')->nullable();
            $table->string('controller')->nullable();
            $table->timestamps();
        });

        // CCTV Detail Table
        Schema::create('cctvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->integer('nvr_channel')->nullable();
            $table->string('firmware')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cctvs');
        Schema::dropIfExists('network_details');
        Schema::dropIfExists('monitors');
        Schema::dropIfExists('printers');
        Schema::dropIfExists('computers');
    }
};
