<x-layout>
    <div class="p-6 bg-white text-black">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Stok Tersedia</h2>
            <a href="{{ route('cashier') }}" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                Kembali ke Kasir
            </a>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <input
                type="text"
                id="searchStock"
                placeholder="Cari produk..."
                class="w-full p-3 mb-4 border border-gray-300 rounded-lg"
            />
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="px-3 py-2">No</th>
                            <th class="px-3 py-2">Produk</th>
                            <th class="px-3 py-2">Harga</th>
                            <th class="px-3 py-2">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($availableStocks as $stockItem)
                            <tr class="border-b stock-row">
                                <td class="px-3 py-2">{{ $loop->iteration }}</td>
                                <td class="px-3 py-2 stock-name">{{ $stockItem->name }}</td>
                                <td class="px-3 py-2">{{ rupiah($stockItem->selling_price) }}</td>
                                <td class="px-3 py-2">{{ $stockItem->stock }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-3 text-gray-500">Tidak ada stok tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchStock');
            const rows = document.querySelectorAll('.stock-row');

            if (!searchInput) return;

            searchInput.addEventListener('input', function () {
                const keyword = searchInput.value.trim().toLowerCase();

                rows.forEach(function (row) {
                    const nameEl = row.querySelector('.stock-name');
                    const productName = nameEl ? nameEl.textContent.toLowerCase() : '';
                    row.style.display = productName.includes(keyword) ? '' : 'none';
                });
            });
        });
    </script>
</x-layout>
