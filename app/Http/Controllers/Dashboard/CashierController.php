<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Settings;
use App\Models\Transactions;
use App\Models\TransactionsItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class CashierController extends Controller
{
    private function getReceiptUrl(string $invoiceCode): ?string
    {
        $filename = 'receipt-' . $invoiceCode . '.pdf';
        $receiptAbsolutePath = storage_path('app/public/receipts/' . $filename);

        if (file_exists($receiptAbsolutePath)) {
            return asset('storage/receipts/' . $filename);
        }

        return route('cashier.receipt', ['invoiceCode' => $invoiceCode]);
    }

    public function receipt(string $invoiceCode)
    {
        $transaction = Transactions::where('invoice_code', $invoiceCode)->firstOrFail();
        $items = TransactionsItems::with('product')
            ->where('transaction_id', $transaction->id)
            ->get();
        $settings = Settings::first();

        return view('dashboard.cashier._partials.receipt', compact('transaction', 'items', 'settings'));
    }

    public function index()
    {
        $products = Products::all();
        $settings = Settings::first();
        $categories = Categories::all();
        $midtransClientKey = config('services.midtrans.client_key');
        $isMidtransProduction = (bool) config('services.midtrans.is_production', false);

        return view('dashboard.cashier.index', compact(
            'products',
            'settings',
            'categories',
            'midtransClientKey',
            'isMidtransProduction'
        ));
    }

    public function stockAvailable()
    {
        $availableStocks = Products::where('stock', '>', 0)
            ->orderBy('name')
            ->get(['id', 'name', 'stock', 'selling_price']);

        return view('dashboard.cashier.stock-available', compact('availableStocks'));
    }

    public function soldProducts()
    {
        $soldProducts = TransactionsItems::with('product:id,name')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.payment_status', 'paid')
            ->select(
                'transaction_items.product_id',
                DB::raw('MAX(transaction_items.price_per_item) as price_per_item'),
                DB::raw('SUM(transaction_items.quantity) as sold_quantity'),
                DB::raw('SUM(transaction_items.subtotal) as sold_total'),
                DB::raw('MAX(COALESCE(transactions.stock_deducted_at, transactions.created_at)) as last_sold_at')
            )
            ->groupBy('transaction_items.product_id')
            ->orderByDesc(DB::raw('MAX(COALESCE(transactions.stock_deducted_at, transactions.created_at))'))
            ->get();

        return view('dashboard.cashier.sold-products', compact('soldProducts'));
    }

    public function filterByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');

        $products = $categoryId == 'all'
            ? Products::all()
            : Products::where('category_id', $categoryId)->get();

        return response()->json(['products' => $products]);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $products = Products::where('name', 'like', "%{$query}%")->get();

        return response()->json(['products' => $products]);
    }

    public function checkout(Request $request)
    {
        // 🔹 Validasi dasar
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_per_item' => 'required|integer|min:0',
            'items.*.discount_per_item' => 'nullable|integer|min:0',
            'items.*.subtotal' => 'required|integer|min:0',
            'total' => 'required|integer|min:0',
            'discount_total' => 'nullable|integer|min:0',
            'grand_total' => 'required|integer|min:0',
            'payment_method' => 'required|string|in:cash,non_cash',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 🔹 Validasi tambahan jika metode pembayaran adalah cash
        if ($request->payment_method === 'cash') {
            $validatorCash = Validator::make($request->all(), [
                'cash_received' => 'required|integer|min:' . $request->grand_total,
                'change' => 'required|integer|min:0',
            ]);
            if ($validatorCash->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi uang tunai gagal.',
                    'errors' => $validatorCash->errors()
                ], 422);
            }
        }

        DB::beginTransaction();

        try {
            // 🔹 Simpan transaksi
            $transaction = Transactions::create([
                'invoice_code' => 'INV-' . strtoupper(uniqid()),
                'total' => $request->total,
                'discount_total' => $request->discount_total ?? 0,
                'grand_total' => $request->grand_total,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'cash' ? 'paid' : 'pending',
                'cash_received' => $request->payment_method === 'cash'
                    ? $request->cash_received
                    : $request->grand_total,
                'change' => $request->payment_method === 'cash'
                    ? ($request->change ?? 0)
                    : 0,
            ]);

            // 🔹 Simpan item transaksi dan update stok
            foreach ($request->items as $item) {
                $product = Products::find($item['product_id']);

                if (!$product) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Produk tidak ditemukan.",
                    ], 400);
                }

                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok untuk produk {$product->name} tidak mencukupi.",
                    ], 400);
                }

                if ($request->payment_method === 'cash') {
                    $product->decrement('stock', $item['quantity']);
                }

                TransactionsItems::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_per_item' => $item['price_per_item'],
                    'discount_per_item' => $item['discount_per_item'] ?? 0,
                    'subtotal' => $item['subtotal'],
                ]);
            }

            if ($request->payment_method === 'cash') {
                $transaction->update(['stock_deducted_at' => now()]);
            }

            DB::commit();

            // 🔹 Pastikan folder receipt ada
            $receiptPath = storage_path('app/public/receipts');
            if (!file_exists($receiptPath)) {
                mkdir($receiptPath, 0777, true);
            }

            // 🔹 Generate PDF
            $settings = Settings::first();
            $items = TransactionsItems::with('product')
                ->where('transaction_id', $transaction->id)
                ->get();

            try {
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('dashboard.cashier._partials.receipt', [
                    'transaction' => $transaction,
                    'items' => $items,
                    'settings' => $settings,
                ]);

                $receiptWidthMm = 48;
                $receiptHeightMm = max(84, 68 + ($items->count() * 9));
                $mmToPoint = 2.83465;
                $pdf->setPaper([
                    0,
                    0,
                    $receiptWidthMm * $mmToPoint,
                    $receiptHeightMm * $mmToPoint,
                ], 'portrait');

                $filename = 'receipt-' . $transaction->invoice_code . '.pdf';
                $pdf->save($receiptPath . '/' . $filename);

                $receiptUrl = asset('storage/receipts/' . $filename);
            } catch (\Exception $e) {
                Log::error('Gagal membuat PDF: ' . $e->getMessage());
                $receiptUrl = $this->getReceiptUrl($transaction->invoice_code);
            }

            $snapToken = null;
            if ($request->payment_method === 'non_cash') {
                $serverKey = config('services.midtrans.server_key');
                $isProduction = (bool) config('services.midtrans.is_production', false);
                $snapUrl = $isProduction
                    ? 'https://app.midtrans.com/snap/v1/transactions'
                    : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

                if (empty($serverKey)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Konfigurasi Midtrans belum lengkap.',
                    ], 500);
                }

                $midtransPayload = [
                    'transaction_details' => [
                        'order_id' => $transaction->invoice_code,
                        'gross_amount' => (int) $transaction->grand_total,
                    ],
                    'customer_details' => [
                        'first_name' => session('name', 'Kasir'),
                        'email' => 'kasir@example.com',
                    ],
                    'enabled_payments' => ['gopay', 'bank_transfer', 'qris', 'credit_card'],
                ];

                $midtransResponse = Http::withBasicAuth($serverKey, '')
                    ->acceptJson()
                    ->post($snapUrl, $midtransPayload);

                if (!$midtransResponse->successful() || !isset($midtransResponse['token'])) {
                    Log::error('Midtrans Snap gagal', [
                        'status' => $midtransResponse->status(),
                        'response' => $midtransResponse->body(),
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membuat transaksi Midtrans.',
                    ], 500);
                }

                $snapToken = $midtransResponse['token'];
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses.',
                'invoice_code' => $transaction->invoice_code,
                'receipt_url' => $receiptUrl,
                'snap_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses transaksi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function completeNonCashPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_code' => 'required|string|exists:transactions,invoice_code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $transaction = Transactions::where('invoice_code', $request->invoice_code)
                ->lockForUpdate()
                ->first();

            if (!$transaction || $transaction->payment_method !== 'non_cash') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi non cash tidak ditemukan.',
                ], 404);
            }

            if ($transaction->stock_deducted_at) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran non cash sudah diproses sebelumnya.',
                    'receipt_url' => $this->getReceiptUrl($transaction->invoice_code),
                ]);
            }

            $items = TransactionsItems::where('transaction_id', $transaction->id)
                ->get();

            foreach ($items as $item) {
                $product = Products::where('id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Produk transaksi tidak ditemukan.',
                    ], 400);
                }

                if ($product->stock < $item->quantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok untuk produk {$product->name} tidak mencukupi saat konfirmasi pembayaran.",
                    ], 400);
                }

                $product->decrement('stock', $item->quantity);
            }

            $transaction->update([
                'payment_status' => 'paid',
                'stock_deducted_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran non cash berhasil dikonfirmasi.',
                'receipt_url' => $this->getReceiptUrl($transaction->invoice_code),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Complete non cash payment error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat konfirmasi pembayaran non cash.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
