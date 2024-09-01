<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class AdminReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        if (Gate::denies('access-admin-dashboard')) {
            abort(403);
        }

        $serverPerformance = $this->getServerPerformance();
        $databasePerformance = $this->getDatabasePerformance();
        $errorLogs = $this->getErrorLogs();
        $uptime = $this->getUptime();

        Log::channel('custom')->info('Admin accessed the dashboard.', [
            'user_id' => auth()->id(),
            'time' => now(),
        ]);

        Log::channel('logs')->info('Admin accessed the dashboard.', [
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
        // Check if sys_getloadavg() is available
        if (function_exists('sys_getloadavg')) {
            $cpuUsage = sys_getloadavg()[0];
        } else {
            // Fallback for environments where sys_getloadavg() is not available
            $cpuUsage = 'N/A'; // or use any other method to determine CPU usage
        }

        // Memory usage is universally available
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
        try {
            return Log::channel('custom')->getLogs()->filter(function ($log) {
                return $log->level == 'error';
            })->take(10)->toArray();
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

    public function getApiPerformanceReport(): JsonResponse
    {
        $this->authorize('accessDashboard', User::class); // Make sure this authorization call is correct

        $averageResponseTime = DB::table('api_performance')->avg('duration');
        $peakResponseTime = DB::table('api_performance')->max('duration');
        $errorRate = DB::table('api_performance')
                ->where('status_code', '>=', 400)
                ->count() / DB::table('api_performance')->count() * 100;

        Log::channel('custom')->info('API performance report generated.', [
            'average_response_time' => $averageResponseTime,
            'peak_response_time' => $peakResponseTime,
            'error_rate' => $errorRate,
            'time' => now(),
        ]);

        Log::channel('logs')->info('API performance report generated.', [
            'average_response_time' => $averageResponseTime,
            'peak_response_time' => $peakResponseTime,
            'error_rate' => $errorRate,
            'time' => now(),
        ]);

        return response()->json([
            'average_response_time' => $averageResponseTime,
            'peak_response_time' => $peakResponseTime,
            'error_rate' => $errorRate,
        ]);
    }
}