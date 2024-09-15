<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class SellerReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user->hasRole('seller')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $startDate = $request->input('start_date', now()->subMonth());
        $endDate = $request->input('end_date', now());

        // Get total sales and order count
        $salesData = $this->getTotalSales($startDate, $endDate);

        // Log report generation
        Log::channel('custom')->info('Seller report generated.', [
            'user_id' => $user->id,
            'time' => now(),
        ]);

        return response()->json([
            'sales' => [
                'total_sales' => $salesData['total_sales'],
                'order_count' => $salesData['order_count'],
            ],
            // You can add more sections like customers, inventory, etc.
        ]);
    }

    private function getTotalSales($startDate, $endDate)
    {
        $totalSales = Order::where('seller_id', auth()->id())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount'); // Summing the total amount of orders

        $orderCount = Order::where('seller_id', auth()->id())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count(); // Counting the number of orders

        return [
            'total_sales' => $totalSales,
            'order_count' => $orderCount,
        ];
    }
}
