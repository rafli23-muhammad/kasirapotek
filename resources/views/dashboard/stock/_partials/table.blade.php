<div class="max-w-full overflow-x-auto">
    <table class="w-full table-auto">
        <thead>
            <tr class="bg-gray-400 text-left">
                <th class="min-w-[150px] px-4 py-4 font-medium text-black">No</th>
                <th class="min-w-[150px] px-4 py-4 font-medium text-black">Nama</th>
                <th class="min-w-[150px] px-4 py-4 font-medium text-black">
                    <div class="flex items-center space-x-2">
                        <span>Stok</span>
                        <form method="GET" action="{{ route('stock') }}">
                            <input type="hidden" name="sort"
                                value="{{ request('sort') === 'asc' ? 'desc' : 'asc' }}">
                            <button type="submit" class="text-gray-600 hover:text-black">
                                @if (request('sort') === 'asc')
                                    <i class="fa-solid fa-arrow-up-wide-short"></i>
                                @else
                                    <i class="fa-solid fa-arrow-down-wide-short"></i>
                                @endif
                            </button>
                        </form>
                    </div>
                </th>
                <th class="min-w-[150px] px-4 py-4 font-medium text-black">Batas Stok Sedikit</th>
                <th class="min-w-[150px] px-4 py-4 font-medium text-black">Status</th>
                <th class="px-4 py-4 font-medium text-black">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td class="border-b px-4 py-5">
                        <p class="text-black">
                            {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                        </p>
                    </td>
                    <td class="border-b px-4 py-5">
                        <p class="text-black">{{ $product->name }}</p>
                    </td>
                    <td class="border-b px-4 py-5">
                        <p class="text-black">{{ $product->stock }}</p>
                    </td>
                    <td class="border-b px-4 py-5">
                        <p class="text-black">{{ $product->low_stock_threshold }}</p>
                    </td>
                    <td class="border-b px-4 py-5">
                        @php
                            $statusClass = match ($product->status) {
                                'Peringatan Stok' => 'bg-red-100 text-red-700',
                                'Stok Hampir Habis' => 'bg-yellow-100 text-yellow-800',
                                'Stok Aman' => 'bg-green-100 text-green-700',
                                default => 'bg-gray-200 text-gray-700',
                            };
                        @endphp

                        <p class="inline-flex rounded-full px-3 py-1 text-sm font-medium {{ $statusClass }}">
                            {{ $product->status }}
                        </p>
                    </td>
                    <td class="border-b px-4 py-5">
                        <div class="flex items-center space-x-3.5">
                            <button data-modal-target="edit-stock-modal-{{ $product->id }}"
                                data-modal-toggle="edit-stock-modal-{{ $product->id }}" class="hover:text-primary">
                                <i class="fa-solid fa-plus text-green-500 bg-gray-300 hover:bg-gray-700 p-4 rounded-full"></i>
                            </button>
                            <a href="{{ route('log-stock', $product->id) }}" class="hover:text-primary">
                              <i class="fa-solid fa-eye text-blue-500 bg-gray-300 hover:bg-gray-700 p-4 rounded-full"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center px-4 py-5 text-gray-500">
                        Tidak ada Produk.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
