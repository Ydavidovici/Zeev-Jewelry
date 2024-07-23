<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Shipping;
use ConsoleTVs\Charts\Facades\Charts;

class ReportController extends Controller
{
    public function index()
    {
        $orders = Order::where('seller_id', auth()->id())->get();
        $products = Product::where('seller_id', auth()->id())->get();
        $payments = Payment::where('seller_id', auth()->id())->get();
        $shippings = Shipping::where('seller_id', auth()->id())->get();

        $orderChart = Charts::database($orders, 'bar', 'highcharts')
            ->title("Orders Report")
            ->elementLabel("Total Orders")
            ->dimensions(1000, 500)
            ->responsive(false)
            ->groupByMonth(date('Y'), true);

        $productChart = Charts::database($products, 'pie', 'highcharts')
            ->title("Products Report")
            ->elementLabel("Total Products")
            ->dimensions(1000, 500)
            ->responsive(false)
            ->groupBy('name');

        $paymentChart = Charts::database($payments, 'line', 'highcharts')
            ->title("Payments Report")
            ->elementLabel("Total Payments")
            ->dimensions(1000, 500)
            ->responsive(false)
            ->groupByMonth(date('Y'), true);

        $shippingChart = Charts::database($shippings, 'bar', 'highcharts')
            ->title("Shippings Report")
            ->elementLabel("Total Shippings")
            ->dimensions(1000, 500)
            ->responsive(false)
            ->groupByMonth(date('Y'), true);

        return view('seller-page.reports.index', compact('orderChart', 'productChart', 'paymentChart', 'shippingChart'));
    }
}
