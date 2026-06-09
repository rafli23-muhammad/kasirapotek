<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Nota</title>
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

        .receipt {
            padding: 4px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
            table-layout: fixed;
        }

        table th,
        table td {
            padding: 1px;
            border-bottom: 1px dashed #000;
            vertical-align: top;
        }

        table th:nth-child(1), table td:nth-child(1) { width: 8%; }
        table th:nth-child(2), table td:nth-child(2) { width: 48%; }
        table th:nth-child(3), table td:nth-child(3) { width: 12%; }
        table th:nth-child(4), table td:nth-child(4) { width: 32%; }

        table th {
            text-align: left;
        }

        hr {
            border: 0;
            border-top: 1px dashed #000;
            margin: 3px 0;
        }

        .totals div {
            display: flex;
            justify-content: space-between;
        }

        .small {
            font-size: 6.5px;
        }

        .mt-2 {
            margin-top: 2px;
        }

        .mt-4 {
            margin-top: 4px;
        }

        .logo-wrap {
            text-align: center;
            width: 100%;
        }

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
        @if ($logoDataUri)
            <div class="logo-wrap">
                <img src="{{ $logoDataUri }}" alt="Logo Toko" class="logo">
            </div>
        @endif
        <div class="text-center bold">{{ $settings->shop_name }}</div>
        <div class="text-center small">{{ $settings->address }}</div>

        <hr>

        <div class="small">
            No: 12345<br>
            {{ now()->format('d-m-Y') }}
        </div>

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
                <tr>
                    <td>1</td>
                    <td>Item Name<br><span class="small">Rp 10.000</span></td>
                    <td>2</td>
                    <td class="text-right" id="subtotal1">Rp 20.000</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Another Item<br><span class="small">Rp 15.000</span></td>
                    <td>1</td>
                    <td class="text-right" id="subtotal2">Rp 15.000</td>
                </tr>
            </tbody>
        </table>

        <hr>

        <div class="totals small">
            <div><span class="bold">TOTAL</span><span id="total">Rp 35.000</span></div>
            <div><span>Pajak ({{ $settings->tax_percentage }}%)</span><span id="tax">Rp 3.500</span></div>
            <div><span>Diskon ({{ $settings->default_discount ?? 0  ?? 0 }}%)</span><span id="discount">Rp 1.750</span></div>
        </div>

        <hr>

        <div class="totals bold">
            <div><span>Total Harga</span><span id="total-price">Rp 36.750</span></div>
        </div>

        <div class="text-center small mt-2">Terima kasih!</div>
    </div>

    <script>
        const subtotal1 = 20000;
        const subtotal2 = 15000;
        const discountPercentage = {{ $settings->default_discount ?? 0 }};
        const taxPercentage = {{ $settings->tax_percentage ?? 0 }};


        const total = subtotal1 + subtotal2;
        const discount = (total * discountPercentage) / 100;
        const tax = (total * taxPercentage) / 100;
        const totalPrice = total + tax - discount;

        document.getElementById('subtotal1').innerText = 'Rp ' + subtotal1.toLocaleString();
        document.getElementById('subtotal2').innerText = 'Rp ' + subtotal2.toLocaleString();
        document.getElementById('total').innerText = 'Rp ' + total.toLocaleString();
        document.getElementById('tax').innerText = 'Rp ' + tax.toLocaleString();
        document.getElementById('discount').innerText = 'Rp ' + discount.toLocaleString();
        document.getElementById('total-price').innerText = 'Rp ' + totalPrice.toLocaleString();
    </script>
</body>

</html>
