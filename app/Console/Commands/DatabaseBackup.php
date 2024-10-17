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

    protected $description = 'Backup the database and project files, excluding vendor, node_modules, and the backups directory, and store it in the backup directory. Keep only the last 7 backups.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Artisan::call('optimize:clear');
        $backupDir = storage_path('app/backups');  // Directory to store backups
        $date = Carbon::now()->format('Y-m-d_H-i-s');
        $dbBackupFile = "{$backupDir}/db_backup_{$date}.sql";
        $projectBackupFile = "{$backupDir}/project_backup_{$date}.tar.gz";
        $zipBackupFile = "{$backupDir}/backup_{$date}.zip"; // Single zip file name

        // Ensure the backup directory exists
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        // Define excluded tables
        $excludeTables = [
            'telescope_entries',
            'telescope_entries_tags',
            'telescope_monitoring',
        ];

        // 1. Backup the database using mysqldump
        if ($this->backupDatabase($dbBackupFile, $excludeTables)) {
            $this->info("Database backup created successfully: {$dbBackupFile}");
        }

        // 2. Backup the project directory excluding vendor, node_modules, and backups
        if ($this->backupProject($projectBackupFile)) {
            $this->info("Project backup created successfully: {$projectBackupFile}");
        }

        // 3. Create a single ZIP file containing both backups
        if ($this->zipBackups($zipBackupFile, $dbBackupFile, $projectBackupFile)) {
            $this->info("Backups zipped successfully: {$zipBackupFile}");
        }

        // 4. Clean up old backups
        $this->deleteOldBackups($backupDir);
    }

    // Backup the database using mysqldump
    private function backupDatabase($backupFile, array $excludeTables)
    {
        $dbHost = env('DB_HOST', 'localhost');
        $dbName = env('DB_DATABASE', 'forge');
        $dbUser = env('DB_USERNAME', 'forge');
        $dbPass = env('DB_PASSWORD', '');

        // Generate the --ignore-table options for excluded tables' data
        $ignoreTablesCommand = [];
        foreach ($excludeTables as $table) {
            $ignoreTablesCommand[] = "--ignore-table={$dbName}.{$table}";
        }

        // Complete mysqldump command
        $command = array_merge(
            [
                'mysqldump',
                '--user=' . $dbUser,
                '--password=' . $dbPass,
                '--host=' . $dbHost,
                $dbName,
                '--result-file=' . $backupFile,
            ],
            $ignoreTablesCommand
        );

        $process = new Process($command);

        try {
            $process->mustRun();
            return true;
        } catch (ProcessFailedException $exception) {
            $this->error('Error creating database backup: ' . $exception->getMessage());
            return false;
        }
    }

    // Backup the project directory, excluding vendor, node_modules, and backups
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

    // Zip both backup files into a single ZIP file
    private function zipBackups($zipFile, $dbBackupFile, $projectBackupFile)
    {
        $command = [
            'zip',
            '-j',                       // Junk the path, store just the names of the files
            $zipFile,                  // Output zip file
            $dbBackupFile,             // Database backup file
            $projectBackupFile         // Project backup file
        ];

        $process = new Process($command);

        try {
            $process->mustRun();
            // Optionally delete individual files after zipping
            unlink($dbBackupFile);
            unlink($projectBackupFile);
            return true;
        } catch (ProcessFailedException $exception) {
            $this->error('Error creating ZIP backup: ' . $exception->getMessage());
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
