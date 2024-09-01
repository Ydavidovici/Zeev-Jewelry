<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class SellerReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!Gate::allows('view-seller-dashboard', $user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $startDate = $request->input('start_date', now()->subMonth());
        $endDate = $request->input('end_date', now());

        // Example Sales Metrics
        $totalSales = $this->getTotalSales($startDate, $endDate);

        // Other metrics...

        Log::channel('custom')->info('Seller report generated.', [
            'user_id' => $user->id,
            'time' => now(),
        ]);

        return response()->json([
            'sales' => [
                'total_sales' => $totalSales,
                // Other sales metrics...
            ],
            // Other report sections...
        ]);
    }

    private function getTotalSales($startDate, $endDate)
    {
        return Order::where('seller_id', auth()->id())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');
    }
}
