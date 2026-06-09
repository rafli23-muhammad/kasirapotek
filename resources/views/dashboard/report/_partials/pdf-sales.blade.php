<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
            word-wrap: break-word;
        }
        th, td {
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 12px;
        }
        th {
            background-color: #4B4B4B;
            color: white;
        }
        h2 {
            margin-bottom: 5px;
        }
        .date-range {
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <h2>Laporan Penjualan</h2>
    <p class="date-range">Periode: {{ $dateText }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Invoice</th>
                <th>Pendapatan</th>
                <th>Diskon</th>
                <th>Pajak</th>
                <th>Total Pendapatan</th>
                <th>Metode Pembayaran</th>
                <th>Transaksi Pada</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaction->invoice_code }}</td>
                    <td>{{ rupiah($transaction->total) }}</td>
                    <td>{{ rupiah($transaction->discount_total) }}</td>
                    <td>{{ rupiah($transaction->total * (($settings->tax_percentage ?? 0) / 100)) }}</td>
                    <td>{{ rupiah($transaction->grand_total) }}</td>
                    <td>{{ $transaction->payment_method }}</td>
                    <td>{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
