<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\TransactionsItems;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use App\Exports\ReportSalesExport;
use App\Models\Settings;
use App\Models\Transactions;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{

    public function index(Request $request)
    {
        $query = TransactionsItems::query();

        $dateRange = $request->get('date_range');


        if ($dateRange && Str::contains($dateRange, ' to ')) {
            [$start, $end] = explode(' to ', $dateRange);
            $startDate = Carbon::parse($start)->startOfDay();
            $endDate = Carbon::parse($end)->endOfDay();
        } else {
            $startDate = now()->subDays(6)->startOfDay();
            $endDate = now()->endOfDay();
            $dateRange = $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d');
        }


        $query->whereBetween('created_at', [$startDate, $endDate]);

        $itemsSold = $query->selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->pluck('total_sold', 'product_id');

        $sortDirection = $request->get('sort') === 'asc' ? 'asc' : 'desc';

        $products = Products::all()->sortBy(function ($product) use ($itemsSold) {
            return $itemsSold[$product->id] ?? 0;
        }, SORT_REGULAR, $sortDirection === 'desc');

        $dateText = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');

        $transactions = Transactions::whereBetween('created_at', [$startDate, $endDate])->get();

        $transactionItems = TransactionsItems::whereBetween('created_at', [$startDate, $endDate])->get();

        $profit = $transactions->sum('grand_total');

        $totalDiscount = $transactions->sum('discount_total');

        $totalCOGS = $transactionItems->sum(function ($item) {
            return ($item->product->purchase_price ?? 0) * $item->quantity;
        });

        $netProfit = $profit - $totalDiscount - $totalCOGS;


        return view('dashboard.report.index', compact('products', 'itemsSold', 'dateText', 'dateRange', 'profit', 'netProfit'));
    }

    public function exportExcel(Request $request)
    {
        [$products, $itemsSold, $dateText, $startDate, $endDate] = $this->getFilteredData($request);

        $formattedDate = "{$startDate->format('Y-m-d')} - {$endDate->format('Y-m-d')}";
        $fileName = 'laporan-produk-' . $formattedDate . '.xlsx';

        return Excel::download(new ReportExport($itemsSold), $fileName);
    }

    private function getFilteredData(Request $request)
    {
        $query = TransactionsItems::query();
        $dateRange = $request->get('date_range');

        if ($dateRange && Str::contains($dateRange, ' to ')) {
            [$start, $end] = explode(' to ', $dateRange);
            $startDate = Carbon::parse($start)->startOfDay();
            $endDate = Carbon::parse($end)->endOfDay();
        } else {
            $startDate = now()->subDays(6)->startOfDay();
            $endDate = now()->endOfDay();
        }

        $query->whereBetween('created_at', [$startDate, $endDate]);

        $itemsSold = $query->selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->pluck('total_sold', 'product_id');

        $products = Products::all()->sortBy(function ($product) use ($itemsSold) {
            return $itemsSold[$product->id] ?? 0;
        }, SORT_REGULAR, true);

        $dateText = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');

        return [$products, $itemsSold, $dateText, $startDate, $endDate];
    }

    public function exportPdf(Request $request)
    {
        [$products, $itemsSold, $dateText, $startDate, $endDate] = $this->getFilteredData($request);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('dashboard.report._partials.pdf', compact('products', 'itemsSold', 'dateText'));
        $pdf->setPaper('a4', 'portrait');

        $formattedDate = "{$startDate->format('Y-m-d')} - {$endDate->format('Y-m-d')}";
        $fileName = 'laporan-produk-' . $formattedDate . '.pdf';

        return $pdf->download($fileName);
    }

    public function reportSales(Request $request)
    {
        $dateRange = $request->get('date_range');
        $settings = Settings::first();

        if ($dateRange && Str::contains($dateRange, ' to ')) {
            [$start, $end] = explode(' to ', $dateRange);
            $startDate = Carbon::parse($start)->startOfDay();
            $endDate = Carbon::parse($end)->endOfDay();
        } else {
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
            $dateRange = $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d');
        }

        $products = Products::all();
        $transactions = Transactions::whereBetween('created_at', [$startDate, $endDate])->get();
        $transactionItems = TransactionsItems::whereBetween('created_at', [$startDate, $endDate])->get();

        $profit = $transactions->sum('grand_total');

        $totalDiscount = $transactions->sum('discount_total');

        $totalCOGS = $transactionItems->sum(function ($item) {
            return ($item->product->purchase_price ?? 0) * $item->quantity;
        });

        $netProfit = $profit - $totalDiscount - $totalCOGS;

        $dateText = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');

        return view('dashboard.report.sales-report', compact('products', 'transactions', 'transactionItems', 'dateRange', 'dateText', 'profit', 'netProfit', 'settings'));
    }

    public function getTransactionItems($transactionId)
    {
        $items = TransactionsItems::where('transaction_id', $transactionId)
            ->with('product')
            ->get()
            ->map(function ($item) {
                return [
                    'product_name' => $item->product->name ?? 'Produk tidak ditemukan',
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                    'subtotal_formatted' => rupiah($item->subtotal),
                ];
            });

        return Response::json($items);
    }

    public function exportExcelSales(Request $request)
    {
        $dateRange = $request->get('date_range');

        if ($dateRange && Str::contains($dateRange, ' to ')) {
            [$start, $end] = explode(' to ', $dateRange);
            $startDate = Carbon::parse($start)->startOfDay();
            $endDate = Carbon::parse($end)->endOfDay();
        } else {
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
            $dateRange = $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d');
        }

        $transactions = Transactions::whereBetween('created_at', [$startDate, $endDate])->get();

        $formattedDate = "{$startDate->format('d M Y')} - {$endDate->format('d M Y')}";

        $fileName = 'laporan-penjualan' . $formattedDate . '.xlsx';

        return Excel::download(new ReportSalesExport($transactions), $fileName);
    }

    public function exportPdfSales(Request $request)
    {
        $dateRange = $request->get('date_range');

        if ($dateRange && Str::contains($dateRange, ' to ')) {
            [$start, $end] = explode(' to ', $dateRange);
            $startDate = Carbon::parse($start)->startOfDay();
            $endDate = Carbon::parse($end)->endOfDay();
        } else {
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
            $dateRange = $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d');
        }

        $transactions = Transactions::whereBetween('created_at', [$startDate, $endDate])->get();
        $dateText = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
        $settings = Settings::first();

        $formattedDate = "{$startDate->format('d M Y')} - {$endDate->format('d M Y')}";

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('dashboard.report._partials.pdf-sales', compact('transactions', 'dateText', 'settings'));
        $pdf->setPaper('a4', 'landscape');

        $fileName = 'laporan-penjualan' . $formattedDate . '.pdf';

        return $pdf->download($fileName);
    }
    
}
