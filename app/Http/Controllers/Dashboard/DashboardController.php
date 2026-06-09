<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Transactions;
use App\Models\TransactionsItems;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'week');
        $endDate = Carbon::now()->endOfDay();

        if ($filter === 'week') {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
        } elseif ($filter === 'month') {
            $startDate = Carbon::now()->subMonth()->startOfDay();
        } elseif ($filter === 'year') {
            $startDate = Carbon::now()->subYear()->startOfDay();
        } else {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
        }

        $transactions = Transactions::whereBetween('created_at', [$startDate, $endDate])->get();
        $transactionItems = TransactionsItems::whereBetween('created_at', [$startDate, $endDate])->get();

        $period = CarbonPeriod::create($startDate, $endDate);
        $days = [];
        foreach ($period as $date) {
            $days[] = $date->format('d M');
        }

        $transactionsData = array_fill(0, count($days), 0);
        $incomeData = array_fill(0, count($days), 0);
        $netProfitData = array_fill(0, count($days), 0);

        foreach ($period as $index => $date) {
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();

            $dailyTransactions = $transactions->whereBetween('created_at', [$dayStart, $dayEnd]);
            $dailyTransactionItems = $transactionItems->whereBetween('created_at', [$dayStart, $dayEnd]);

            $dailyGrandTotal = $dailyTransactions->sum('grand_total');
            $dailyDiscountTotal = $dailyTransactions->sum('discount_total');
            $dailyCOGS = $dailyTransactionItems->sum(function ($item) {
                return ($item->product->purchase_price ?? 0) * $item->quantity;
            });

            $transactionsData[$index] = $dailyTransactions->count();
            $incomeData[$index] = $dailyTransactions->sum('total');
            $netProfitData[$index] = $dailyGrandTotal - $dailyDiscountTotal - $dailyCOGS;
        }

        $expiredCount = Products::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', Carbon::today())
            ->count();

        $nearExpiredCount = Products::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '>=', Carbon::today())
            ->whereDate('expiry_date', '<=', Carbon::today()->addDays(30))
            ->count();

        return view('dashboard.index.index', [
            'days' => $days,
            'netProfit' => $netProfitData,
            'transactionsData' => $transactionsData,
            'incomeData' => $incomeData,
            'expiredCount' => $expiredCount,
            'nearExpiredCount' => $nearExpiredCount,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'filter' => $filter
        ]);
    }

    public function filter(Request $request)
{
    $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
    $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
    $filter = $request->input('filter');

    $transactions = Transactions::whereBetween('created_at', [$startDate, $endDate])->get();
    $transactionItems = TransactionsItems::whereBetween('created_at', [$startDate, $endDate])->get();

    $labels = [];
    $transactionsData = [];
    $incomeData = [];
    $netProfitData = [];
    $period = null;

    if ($filter === 'week') {
        $period = CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $labels[] = $date->format('d M');

            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();

            $dailyTransactions = $transactions->whereBetween('created_at', [$dayStart, $dayEnd]);
            $dailyTransactionItems = $transactionItems->whereBetween('created_at', [$dayStart, $dayEnd]);

            $dailyGrandTotal = $dailyTransactions->sum('grand_total');
            $dailyDiscountTotal = $dailyTransactions->sum('discount_total');
            $dailyCOGS = $dailyTransactionItems->sum(function ($item) {
                return ($item->product->purchase_price ?? 0) * $item->quantity;
            });

            $transactionsData[] = $dailyTransactions->count();
            $incomeData[] = $dailyTransactions->sum('total');
            $netProfitData[] = $dailyGrandTotal - $dailyDiscountTotal - $dailyCOGS;
        }
    } elseif ($filter === 'month') {
        $weekStart = $startDate->copy()->startOfWeek();
        while ($weekStart < $endDate) {
            $weekEnd = $weekStart->copy()->endOfWeek();
            $labels[] = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M');

            $dayStart = $weekStart->copy()->startOfDay();
            $dayEnd = $weekEnd->copy()->endOfDay();

            $dailyTransactions = $transactions->whereBetween('created_at', [$dayStart, $dayEnd]);
            $dailyTransactionItems = $transactionItems->whereBetween('created_at', [$dayStart, $dayEnd]);

            $dailyGrandTotal = $dailyTransactions->sum('grand_total');
            $dailyDiscountTotal = $dailyTransactions->sum('discount_total');
            $dailyCOGS = $dailyTransactionItems->sum(function ($item) {
                return ($item->product->purchase_price ?? 0) * $item->quantity;
            });

            $transactionsData[] = $dailyTransactions->count();
            $incomeData[] = $dailyTransactions->sum('total');
            $netProfitData[] = $dailyGrandTotal - $dailyDiscountTotal - $dailyCOGS;

            $weekStart->addWeek();
        }
    } elseif ($filter === 'year') {
        $startMonth = $startDate->copy()->startOfMonth();
        while ($startMonth < $endDate) {
            $endMonth = $startMonth->copy()->endOfMonth();
            $labels[] = $startMonth->format('M Y');

            $dayStart = $startMonth->copy()->startOfDay();
            $dayEnd = $endMonth->copy()->endOfDay();

            $monthlyTransactions = $transactions->whereBetween('created_at', [$dayStart, $dayEnd]);
            $monthlyTransactionItems = $transactionItems->whereBetween('created_at', [$dayStart, $dayEnd]);

            $monthlyGrandTotal = $monthlyTransactions->sum('grand_total');
            $monthlyDiscountTotal = $monthlyTransactions->sum('discount_total');
            $monthlyCOGS = $monthlyTransactionItems->sum(function ($item) {
                return ($item->product->purchase_price ?? 0) * $item->quantity;
            });

            $transactionsData[] = $monthlyTransactions->count();
            $incomeData[] = $monthlyTransactions->sum('total');
            $netProfitData[] = $monthlyGrandTotal - $monthlyDiscountTotal - $monthlyCOGS;

            $startMonth->addMonth();
        }
    }

    return response()->json([
        'labels' => $labels,
        'transactionsData' => $transactionsData,
        'incomedata' => $incomeData, 
        'netprofit' => $netProfitData, 
        'startDate' => $startDate->format('d M Y'),
        'endDate' => $endDate->format('d M Y'),
    ]);
}

}
