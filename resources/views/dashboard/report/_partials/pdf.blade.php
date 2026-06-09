<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Produk</title>
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
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4B4B4B;
            color: white;
        }
        .table-header {
            font-weight: bold;
        }
        .container {
            margin-top: 20px;
        }
        .date-range {
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Laporan Produk</h2>
        <p class="date-range">Periode: {{ $dateText }}</p>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Product</th>
                    <th>Terjual</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $itemsSold[$product->id] ?? 0 }} terjual</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center;">Tidak ada Produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
