<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Backup the database and store it in storage';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get the database connection
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        // Set the filename for the backup
        $filename = "backup-" . now()->format('Y-m-d-H-i-s') . ".sql";
        $path = storage_path("app/backups/{$filename}");

        // Create the backup directory if it doesn't exist
        Storage::makeDirectory('backups');

        // Run the mysqldump command
        $command = "mysqldump --user={$username} --password={$password} --host={$host} {$database} > {$path}";
        $result = null;
        $output = null;
        exec($command, $output, $result);

        if ($result === 0) {
            $this->info("Backup was successful! Saved to {$path}");
        } else {
            $this->error("Backup failed with code {$result}");
        }
    }
}
