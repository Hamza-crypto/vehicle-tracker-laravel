<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DatabaseBackup extends Command
{
    protected $signature = 'backup:full';

    protected $description = 'Backup the database and project files, excluding vendor and node_modules, and store it in the backup directory. Keep only the last 7 backups.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Artisan::call('optimize:clear');
        $backupDir = storage_path('app/backups');  // Directory to store backups
        $date = Carbon::now()->format('Y-m-d_H-i-s');
        $dbBackupFile = $backupDir . "/db_backup_{$date}.sql";
        $projectBackupFile = $backupDir . "/project_backup_{$date}.tar.gz";

        // Ensure the backup directory exists
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        // 1. Backup the database using mysqldump
        if ($this->backupDatabase($dbBackupFile)) {
            $this->info("Database backup created successfully: {$dbBackupFile}");
        }

        // 2. Backup the project directory excluding vendor and node_modules
        if ($this->backupProject($projectBackupFile)) {
            $this->info("Project backup created successfully: {$projectBackupFile}");
        }

        // 3. Clean up old backups
        $this->deleteOldBackups($backupDir);
    }

    // Backup the database using mysqldump
    private function backupDatabase($backupFile)
    {
        $dbHost = env('DB_HOST', 'localhost');
        $dbName = env('DB_DATABASE', 'forge');
        $dbUser = env('DB_USERNAME', 'forge');
        $dbPass = env('DB_PASSWORD', '');

        // Tables to exclude from the backup
        $excludeTables = [
            'telescope_entries',
            'telescope_entries_tags	',
            'telescope_monitoring'
        ];

        // Generate the --ignore-table options for excluded tables' data
        $ignoreTablesCommand = '';
        foreach ($excludeTables as $table) {
            $ignoreTablesCommand .= " --ignore-table={$dbName}.{$table}";
        }

        // Generate a command to back up the structure of excluded tables
        $structureCommand = '';
        foreach ($excludeTables as $table) {
            $structureCommand .= " --no-data --tables {$dbName}.{$table}";
        }

        $command = [
            'mysqldump',
            '--user=' . $dbUser,
            '--password=' . $dbPass,
            '--host=' . $dbHost,
            $ignoreTablesCommand,
            $dbName,
            '--result-file=' . $backupFile,
            $structureCommand,
        ];

        $process = new Process($command);

        try {
            $process->mustRun();
            return true;
        } catch (ProcessFailedException $exception) {
            $this->error('Error creating database backup: ' . $exception->getMessage());
            return false;
        }
    }

    // Backup the project directory, excluding vendor and node_modules
    private function backupProject($backupFile)
    {
        $projectRoot = base_path(); // Get the Laravel project's root directory

        $command = [
            'tar',
            '--exclude=vendor',          // Exclude vendor folder
            '--exclude=node_modules',    // Exclude node_modules folder
            '--exclude=storage/app/backups', // Exclude backups directory
            '-czf',                      // Create compressed .tar.gz archive
            $backupFile,                 // Output file
            '-C',                        // Change directory to project root
            $projectRoot,                // The directory to back up
            '.'                          // Include everything from the project root
        ];

        $process = new Process($command);

        try {
            $process->mustRun();
            return true;
        } catch (ProcessFailedException $exception) {
            $this->error('Error creating project backup: ' . $exception->getMessage());
            return false;
        }
    }

    // Deletes backups older than 7 days
    private function deleteOldBackups($backupDir)
    {
        $files = collect(Storage::files('backups'))
            ->sortByDesc(function ($file) {
                return filemtime(storage_path('app/' . $file));
            })
            ->values();

        // Only keep the latest 7 files
        if ($files->count() > 7) {
            $oldBackups = $files->slice(7);
            foreach ($oldBackups as $oldBackup) {
                Storage::delete($oldBackup);
                $this->info("Deleted old backup: " . storage_path('app/' . $oldBackup));
            }
        }
    }
}
