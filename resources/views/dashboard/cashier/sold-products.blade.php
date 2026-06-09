<x-layout>
    <div class="p-6 bg-white text-black">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Produk Terjual</h2>
            <a href="{{ route('cashier') }}" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                Kembali ke Kasir
            </a>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <input
                type="text"
                id="searchSoldProducts"
                placeholder="Cari produk terjual..."
                class="w-full p-3 mb-4 border border-gray-300 rounded-lg"
            />
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="px-3 py-2">No</th>
                            <th class="px-3 py-2">Produk</th>
                            <th class="px-3 py-2">Harga</th>
                            <th class="px-3 py-2">Quantity</th>
                            <th class="px-3 py-2">Total Harga</th>
                            <th class="px-3 py-2">Tanggal & Jam Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($soldProducts as $soldItem)
                            <tr class="border-b sold-row">
                                <td class="px-3 py-2">{{ $loop->iteration }}</td>
                                <td class="px-3 py-2 sold-name">{{ optional($soldItem->product)->name ?? '-' }}</td>
                                <td class="px-3 py-2">{{ rupiah($soldItem->price_per_item) }}</td>
                                <td class="px-3 py-2">{{ $soldItem->sold_quantity }}</td>
                                <td class="px-3 py-2">{{ rupiah($soldItem->sold_total) }}</td>
                                <td class="px-3 py-2">
                                    {{ $soldItem->last_sold_at ? \Carbon\Carbon::parse($soldItem->last_sold_at)->format('d-m-Y H:i:s') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-3 text-gray-500">Belum ada produk terjual.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchSoldProducts');
            const rows = document.querySelectorAll('.sold-row');

            if (!searchInput) return;

            searchInput.addEventListener('input', function () {
                const keyword = searchInput.value.trim().toLowerCase();

                rows.forEach(function (row) {
                    const nameEl = row.querySelector('.sold-name');
                    const productName = nameEl ? nameEl.textContent.toLowerCase() : '';
                    row.style.display = productName.includes(keyword) ? '' : 'none';
                });
            });
        });
    </script>
</x-layout>
