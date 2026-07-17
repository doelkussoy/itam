<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'username')) {
            // 1. Add column as nullable
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->nullable()->after('name');
            });

            // 2. Set usernames for existing users using email prefix
            $users = DB::table('users')->get();
            foreach ($users as $user) {
                $baseUsername = explode('@', $user->email)[0];
                // Clean base username (only allow alphanumeric, dots, dashes, underscores)
                $baseUsername = preg_replace('/[^a-zA-Z0-9._-]/', '', $baseUsername);
                if (empty($baseUsername)) {
                    $baseUsername = 'user';
                }
                
                $username = $baseUsername;
                $counter = 1;
                while (DB::table('users')->where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }
                DB::table('users')->where('id', $user->id)->update(['username' => $username]);
            }

            // 3. Make the column NOT NULL and UNIQUE
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->nullable(false)->unique()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('username');
            });
        }
    }
};
