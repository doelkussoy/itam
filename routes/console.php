<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

try {
    if (Schema::hasTable('settings')) {
        $autoBackup = Setting::where('key', 'auto_database_backup')->value('value');
        if ($autoBackup == '1') {
            Schedule::command('db:backup')->dailyAt('00:00');
        }
    }
} catch (\Exception $e) {
    // Ignore if table doesn't exist yet
}
