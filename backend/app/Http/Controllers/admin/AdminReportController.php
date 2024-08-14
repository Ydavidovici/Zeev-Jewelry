<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAdminDashboard', auth()->user());

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
        // Example of server performance metrics
        return [
            'cpu_usage' => sys_getloadavg()[0],
            'memory_usage' => memory_get_usage(true) / 1024 / 1024 . ' MB',
        ];
    }

    private function getDatabasePerformance()
    {
        // Example of a simple database performance metric
        $slowQueries = DB::select("SHOW FULL PROCESSLIST");
        $slowQueryCount = collect($slowQueries)->where('Time', '>', 5)->count(); // Example threshold of 5 seconds

        return [
            'slow_query_count' => $slowQueryCount,
        ];
    }

    private function getErrorLogs()
    {
        // Example of fetching recent errors from logs
        return Log::channel('custom')->getLogs()->filter(function ($log) {
            return $log->level == 'error';
        })->take(10)->toArray(); // Limit to last 10 errors
    }

    private function getUptime()
    {
        // Example of uptime monitoring
        $uptime = shell_exec("uptime");

        return [
            'uptime' => $uptime,
        ];
    }

    public function getApiPerformanceReport(): JsonResponse
    {
        $this->authorize('viewAdminDashboard', auth()->user());

        $averageResponseTime = DB::table('api_performance')->avg('duration');
        $peakResponseTime = DB::table('api_performance')->max('duration');
        $errorRate = DB::table('api_performance')
                ->where('status_code', '>=', 400)
                ->count() / DB::table('api_performance')->count() * 100;

        // Log API performance metrics
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
