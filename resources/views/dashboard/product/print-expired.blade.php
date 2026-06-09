<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen Obat Expired</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #111; }
        h1 { margin-bottom: 4px; }
        p { margin-top: 0; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f3f4f6; }
        .expired { color: #b91c1c; font-weight: bold; }
        .near { color: #b45309; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Dokumen Obat Hampir Expired / Expired</h1>
    <p>Tanggal cetak: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Tanggal Kedaluwarsa</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ optional($product->category)->name ?? '-' }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ optional($product->expiry_date)->format('d-m-Y') ?? '-' }}</td>
                    <td class="{{ $product->expiry_status === 'Expired' ? 'expired' : 'near' }}">{{ $product->expiry_status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Tidak ada data obat expired / hampir expired.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>
