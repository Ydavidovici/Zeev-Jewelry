<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use ConsoleTVs\Charts\Facades\Charts;

class ReportController extends Controller
{
    public function index()
    {
        $chart = Charts::database(Order::all(), 'bar', 'highcharts')
            ->title("Orders Report")
            ->elementLabel("Total Orders")
            ->dimensions(1000, 500)
            ->responsive(false)
            ->groupByMonth(date('Y'), true);

        return view('admin-page.reports.index', compact('chart'));
    }
}
