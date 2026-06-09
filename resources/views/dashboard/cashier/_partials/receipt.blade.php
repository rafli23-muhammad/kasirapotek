<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <style>
        @page { margin: 0; }
        body {
            width: 48mm;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 7px;
            line-height: 1.25;
            background: #fff;
            overflow-wrap: break-word;
        }
        .receipt { padding: 4px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .shop-name { font-size: 8.5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 2px; table-layout: fixed; }
        table th, table td { padding: 1px; border-bottom: 1px dashed #000; vertical-align: top; }
        table th:nth-child(1), table td:nth-child(1) { width: 8%; }
        table th:nth-child(2), table td:nth-child(2) { width: 48%; }
        table th:nth-child(3), table td:nth-child(3) { width: 12%; }
        table th:nth-child(4), table td:nth-child(4) { width: 32%; }
        hr { border: 0; border-top: 1px dashed #000; margin: 3px 0; }
        .small { font-size: 6.5px; }
        .totals div { display: flex; justify-content: space-between; }
        .mt-2 { margin-top: 2px; }
        .logo-wrap { text-align: center; width: 100%; }
        .logo {
            width: 18px;
            height: 18px;
            object-fit: contain;
            display: inline-block;
            margin: 0 auto 2px;
        }
    </style>
</head>
<body>
    @php
        $logoDataUri = null;
        if (!empty($settings->logo)) {
            $logoPath = storage_path('app/public/' . $settings->logo);
            if (file_exists($logoPath)) {
                $extension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
                $mimeType = match ($extension) {
                    'jpg', 'jpeg' => 'image/jpeg',
                    'gif' => 'image/gif',
                    'webp' => 'image/webp',
                    default => 'image/png',
                };
                $logoDataUri = 'data:' . $mimeType . ';base64,' . base64_encode(file_get_contents($logoPath));
            }
        }
    @endphp

    <div class="receipt">
        {{-- Header --}}
        @if ($logoDataUri)
            <div class="logo-wrap">
                <img src="{{ $logoDataUri }}" alt="Logo Toko" class="logo">
            </div>
        @endif
        <div class="text-center bold shop-name">{{ $settings->shop_name ?? 'Nama Toko' }}</div>
        <div class="text-center small">{{ $settings->address ?? 'Alamat Toko' }}</div>

        <hr>

        {{-- Info Transaksi --}}
        <div class="small">
            No: {{ $transaction->invoice_code ?? '-' }}<br>
            {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
        </div>

        {{-- Daftar Item --}}
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ optional($item->product)->name ?? '-' }}<br>
                            <span class="small">Rp {{ number_format($item->price_per_item ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td>{{ $item->quantity ?? 0 }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center small">Tidak ada item</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <hr>

        {{-- Total --}}
        <div class="totals small">
            <div>
                <span class="bold">TOTAL</span>
                <span>Rp {{ number_format($transaction->total ?? 0, 0, ',', '.') }}</span>
            </div>

            @if (($transaction->discount_total ?? 0) > 0)
                <div>
                    <span>Diskon</span>
                    <span>Rp {{ number_format($transaction->discount_total ?? 0, 0, ',', '.') }}</span>
                </div>
            @endif
        </div>

        <hr>

        {{-- Pembayaran --}}
        <div class="totals small">
            <div>
                <span>Bayar ({{ ucfirst($transaction->payment_method ?? '-') }})</span>
                <span>Rp {{ number_format($transaction->cash_received ?? 0, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="bold">Total Harga</span>
                <span>Rp {{ number_format($transaction->grand_total ?? 0, 0, ',', '.') }}</span>
            </div>
            <div>
                <span>Kembalian</span>
                <span>Rp {{ number_format($transaction->change ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <hr>

        <div class="text-center small mt-2">Terima kasih telah berbelanja!</div>
    </div>
</body>
</html>
