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
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('supervisor_id')->nullable()->constrained('employees')->onDelete('set null')->after('position_id');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null')->after('supervisor_id');
            $table->string('extension')->nullable()->after('location_id');
            $table->string('anydesk_id')->nullable()->after('extension');
            $table->string('anydesk_password')->nullable()->after('anydesk_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->dropForeign(['location_id']);
            $table->dropColumn(['supervisor_id', 'location_id', 'extension', 'anydesk_id', 'anydesk_password']);
        });
    }
};
