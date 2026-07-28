<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Set all existing pic_id to null to prevent constraint violation
        DB::table('tickets')->update(['pic_id' => null]);

        Schema::table('tickets', function (Blueprint $table) {
            $table->foreign('pic_id')->references('id')->on('pics')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['pic_id']);
            $table->foreign('pic_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::dropIfExists('pics');
    }
};
