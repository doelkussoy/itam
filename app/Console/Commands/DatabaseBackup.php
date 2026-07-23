<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        
        $storagePath = storage_path('app/backups');
        
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
        
        $filePath = $storagePath . '/' . $filename;
        
        $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " > " . $filePath;
        
        $returnVar = NULL;
        $output  = NULL;
        
        exec($command, $output, $returnVar);
        
        if ($returnVar == 0) {
            $this->info("Database backup created successfully: $filename");
            Log::info("Auto Database Backup created: $filename");
            
            // Clean up old backups (keep last 7 days)
            $this->cleanOldBackups($storagePath);
        } else {
            $this->error("Database backup failed!");
            Log::error("Auto Database Backup failed! Command: $command");
        }
    }
    
    private function cleanOldBackups($path)
    {
        $files = glob($path . '/*.sql');
        $now   = time();
        
        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= 60 * 60 * 24 * 7) { // 7 days
                    unlink($file);
                }
            }
        }
    }
}
