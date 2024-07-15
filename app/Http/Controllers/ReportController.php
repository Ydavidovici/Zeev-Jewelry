<?php

namespace App\Http\Controllers;

use App\Models\Order;
use ConsoleTVs\Charts\Facades\Charts;
use Illuminate\Http\Request;

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

        return view('admin.reports.index', compact('chart'));
    }
}
