<div class="max-w-full overflow-x-auto">
    <table class="w-full table-auto">
        <thead>
            <tr class="bg-gray-400 text-left">
                <th class="min-w-[50px] px-4 py-4 font-medium text-black">No</th>
                <th class="min-w-[150px] px-4 py-4 font-medium text-black">Kode Invoice</th>
                <th class="min-w-[150px] px-4 py-4 font-medium text-black">Pendapatan</th>
                <th class="min-w-[150px] px-4 py-4 font-medium text-black">Diskon</th>
                <th class="min-w-[150px] px-4 py-4 font-medium text-black">Pajak</th>
                <th class="min-w-[150px] px-4 py-4 font-medium text-black">Total Pendapatan</th>
                <th class="min-w-[150px] px-4 py-4 font-medium text-black">Metode Pembayaran</th>
                <th class="min-w-[150px] px-4 py-4 font-medium text-black">Transaksi Pada</th>
                <th class="min-w-[150px] px-4 py-4 font-medium text-black">Detail Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td class="border-b px-4 py-5">
                        <p class="text-black">{{ $loop->iteration }}</p>
                    </td>
                    <td class="border-b px-4 py-5">
                        <p class="text-black">{{ $transaction->invoice_code }}</p>
                    </td>
                    <td class="border-b px-4 py-5">
                        <p class="text-black">{{ rupiah($transaction->total) }}</p>
                    </td>
                    <td class="border-b px-4 py-5">
                        <p class="text-black">-{{ rupiah($transaction->discount_total) }}</p>
                    </td>
                    <td class="border-b px-4 py-5">
                        <p class="text-black">
    {{ rupiah($transaction->total / ($settings->tax_percentage ?? 1)) }}
</p>
                    </td>
                    <td class="border-b px-4 py-5">
                        <p class="text-black">{{ rupiah($transaction->grand_total) }}</p>
                    </td>
                    <td class="border-b px-4 py-5">
                        <p class="text-black">{{ $transaction->payment_method }}</p>
                    </td>
                    <td class="border-b px-4 py-5">
                        <p class="text-black">{{ $transaction->created_at }}</p>
                    </td>
                    <td class="border-b px-4 py-5">
                        <button onclick="openModal({{ $transaction->id }})"
                            class="border border-blue-400 text-blue-500 p-2 px-4 rounded-full hover:bg-blue-500 hover:text-white">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center px-4 py-5 text-gray-500">
                        Tidak ada Penjualan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div id="transactionModal"
        class="hidden fixed inset-0 bg-black text-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-lg w-full relative">
            <h2 class="text-xl font-bold mb-4">Detail Transaksi</h2>
            <div id="transactionItems" class="space-y-2">
            </div>
            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
                ✖
            </button>
        </div>
    </div>

</div>

<script>
    function openModal(transactionId) {
        const transactionItemsDiv = document.getElementById('transactionItems');
        transactionItemsDiv.innerHTML = '<p class="text-gray-500">Loading...</p>';

        document.getElementById('transactionModal').classList.remove('hidden');

        fetch(`/transaction-items/${transactionId}`)
            .then(response => response.json())
            .then(data => {
                transactionItemsDiv.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(item => {
                        const itemDiv = document.createElement('div');
                        itemDiv.className = 'p-2 border rounded';
                        itemDiv.innerHTML = `
                          <p><strong>Produk:</strong> ${item.product_name}</p>
                          <p><strong>Jumlah:</strong> ${item.quantity}</p>
                          <p><strong>Subtotal:</strong> ${item.subtotal_formatted}</p>
                      `;
                        transactionItemsDiv.appendChild(itemDiv);
                    });
                } else {
                    transactionItemsDiv.innerHTML =
                        '<p class="text-gray-500">Tidak ada item untuk transaksi ini.</p>';
                }
            })
            .catch(error => {
                console.error(error);
                transactionItemsDiv.innerHTML = '<p class="text-red-500">Gagal mengambil data.</p>';
            });
    }

    function closeModal() {
        document.getElementById('transactionModal').classList.add('hidden');
    }
</script>
