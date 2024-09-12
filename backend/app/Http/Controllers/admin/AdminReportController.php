<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AdminReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized.');
        }

        // Get performance data and logs
        $serverPerformance = $this->getServerPerformance();
        $databasePerformance = $this->getDatabasePerformance();
        $errorLogs = $this->getErrorLogs();
        $uptime = $this->getUptime();

        // Log admin access
        Log::channel('custom')->info('Admin accessed the dashboard.', [
            'user_id' => auth()->id(),
            'time' => now(),
        ]);

        return response()->json([
            'server_performance' => $serverPerformance,
            'database_performance' => $databasePerformance,
            'error_logs' => $errorLogs,
            'uptime' => $uptime,
        ]);
    }

    private function getServerPerformance()
    {
        if (function_exists('sys_getloadavg')) {
            $cpuUsage = sys_getloadavg()[0];
        } else {
            $cpuUsage = 'N/A';
        }

        $memoryUsage = memory_get_usage(true) / 1024 / 1024 . ' MB';

        return [
            'cpu_usage' => $cpuUsage,
            'memory_usage' => $memoryUsage,
        ];
    }

    private function getDatabasePerformance()
    {
        try {
            $slowQueries = DB::select("SHOW FULL PROCESSLIST");
            $slowQueryCount = collect($slowQueries)->where('Time', '>', 5)->count();
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error fetching database performance: ' . $e->getMessage());
            $slowQueryCount = 'N/A';
        }

        return [
            'slow_query_count' => $slowQueryCount,
        ];
    }

    private function getErrorLogs()
    {
        // Define the path to the log file (adjust as needed)
        $logFilePath = storage_path('logs/laravel.log'); // Adjust this if your custom channel uses a different file

        // Check if the log file exists
        if (!file_exists($logFilePath)) {
            return [];
        }

        // Read the log file
        try {
            $logFileContents = file($logFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            // Filter the logs for 'error' level logs
            $errorLogs = array_filter($logFileContents, function ($line) {
                return strpos($line, 'ERROR') !== false;
            });

            // Return the last 10 error logs
            return array_slice($errorLogs, -10);
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error fetching error logs: ' . $e->getMessage());
            return [];
        }
    }


    private function getUptime()
    {
        try {
            $uptime = shell_exec("uptime");
            return [
                'uptime' => $uptime,
            ];
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error fetching system uptime: ' . $e->getMessage());
            return [
                'uptime' => 'N/A',
            ];
        }
    }
}
