<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();

        $todayTotalSales = Transaction::whereDate('booking_date', Carbon::today())
            ->where('status', 'confirmed')
            ->sum('final_total');

        $totalSales = Transaction::where('status', 'confirmed')->sum('final_total');

        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays(29);

        $dateRange = [];
        for ($i = 0; $i < 30; $i++) {
            $dateRange[] = $startDate->copy()->addDays($i)->format('Y-m-d');
        }

        $transactions = Transaction::select(
            DB::raw('DATE(booking_date) as date'),
            DB::raw('SUM(final_total) as total_sales')
        )
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->where('status', 'confirmed')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('total_sales', 'date')
            ->toArray();

        $labels = $dateRange;
        $all_sell_values = array_map(function ($date) use ($transactions) {
            return isset($transactions[$date]) ? $transactions[$date] : 0;
        }, $dateRange);

        return view('backends.index', compact('labels', 'all_sell_values', 'totalProducts', 'totalCustomers', 'todayTotalSales', 'totalSales'));
    }
}
