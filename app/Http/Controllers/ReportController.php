<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));

        $sales = Sale::with('details.productSize.product')
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $sales->sum('total');
        $totalSales = $sales->count();

        // Chart Data: Hourly Sales
        $chartData = $sales->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('H:00');
        })->map(function ($row) {
            return $row->sum('total');
        });

        // Fill missing hours
        $hours = [];
        $amounts = [];
        for ($i = 8; $i <= 22; $i++) { // Store hours approx 8am to 10pm
            $h = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            $hours[] = $h;
            $amounts[] = $chartData->get($h, 0);
        }

        return view('reports.daily', compact('sales', 'date', 'totalRevenue', 'totalSales', 'hours', 'amounts'));
    }

    public function monthly(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Group sales by day
        $dailySales = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count'),
            DB::raw('sum(total) as total')
        )
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $totalRevenue = $dailySales->sum('total');
        $totalTransactions = $dailySales->sum('count');
        $averageTicket = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        $maxDailyRevenue = $dailySales->max('total') ?? 0;

        // Chart Data
        $chartLabels = $dailySales->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('d');
        })->reverse()->values(); // Reverse to have 1..31 order

        $chartValues = $dailySales->pluck('total')->reverse()->values();

        return view('reports.monthly', compact(
            'dailySales',
            'month',
            'year',
            'totalRevenue',
            'totalTransactions',
            'averageTicket',
            'maxDailyRevenue',
            'chartLabels',
            'chartValues'
        ));
    }
}
