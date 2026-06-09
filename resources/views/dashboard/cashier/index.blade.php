<x-layout>
    <div class="p-6 bg-white text-black">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Selamat Datang {{ session('name') }}
            </h2>
            <h1 id="clock" class="text-2xl font-bold text-gray-800">--:--:--</h1>
        </div>

        <div class="flex gap-6">
            {{-- Kiri: Produk --}}
            <div class="w-2/3">
                {{-- Filter kategori --}}
                <select id="categoryFilter" class="w-full p-3 mb-4 border border-gray-300 rounded-lg">
                    <option value="all">Pilih Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                {{-- Search --}}
                <input autocomplete="off" type="text" id="search" placeholder="Cari..."
                    class="w-full p-3 mb-4 border border-gray-300 rounded-lg" />

                {{-- Daftar Produk --}}
                <div id="productList" class="grid grid-cols-4 gap-4">
                    @foreach ($products as $product)
                        <div class="border rounded-lg p-3 text-center shadow hover:shadow-lg transition"
                            data-product-id="{{ $product->id }}"
                            data-product-display-name="{{ $product->name }}"
                            data-product-name="{{ strtolower($product->name) }}"
                            data-category-id="{{ $product->category_id }}"
                            data-product-description="{{ $product->description ?? '' }}"
                            data-product-price="{{ $product->selling_price }}"
                            data-product-stock="{{ $product->stock }}"
                            data-product-expiry="{{ $product->expiry_date ? $product->expiry_date->format('d-m-Y') : '-' }}">
                            {{-- Gambar --}}
                            @if ($product->image && file_exists(public_path('storage/' . $product->image)))
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="h-24 mx-auto mb-2 object-contain cursor-pointer" data-product-info-trigger />
                            @else
                                <div class="h-24 w-full bg-gray-200 flex items-center justify-center mb-2 cursor-pointer"
                                    data-product-info-trigger>
                                    <span class="text-gray-500 text-sm">Tidak ada gambar</span>
                                </div>
                            @endif

                            {{-- Info Produk --}}
                            <p class="font-semibold text-sm cursor-pointer" data-product-info-trigger>{{ $product->name }}</p>
                            <p class="font-semibold text-sm">Stok: <span data-stock-value>{{ $product->stock }}</span></p>
                            <p class="text-blue-600 font-bold mt-1">{{ rupiah($product->selling_price) }}</p>

                            {{-- Tombol Tambah --}}
                            <button
                                onclick="addToCart('{{ $product->name }}', {{ $product->selling_price }}, {{ $product->id }})"
                                data-add-to-cart
                                class="mt-2 px-3 py-1 text-sm {{ $product->stock != 0 ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-400' }} text-white rounded transition"
                                @if ($product->stock == 0) disabled @endif>
                                {{ $product->stock == 0 ? 'Stok Habis' : 'Tambah' }}
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Kanan: Keranjang --}}
            <div class="w-1/3 bg-gray-100 p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-4">🛒 Umum</h2>

                {{-- Item keranjang --}}
                <div id="cartItems" class="space-y-4 mb-4 max-h-64 overflow-y-auto"></div>

                {{-- Ringkasan --}}
                <div class="border-t pt-2 text-sm">
                    <div class="flex justify-between">
                        <span>Sub Total</span>
                        <span id="subTotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pajak ({{ $settings->tax_percentage ?? 0 }}%)</span>
                        <span id="taxAmount">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Diskon ({{ $settings->default_discount ?? 0  ?? 0 ?? 0 }}%)</span>
                        <span id="discountAmount">-Rp 0</span>
                    </div>
                    <div class="flex justify-between font-bold text-purple-700 mt-2 text-lg">
                        <span>Total</span>
                        <span id="total">Rp 0</span>
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="mt-4 mb-4">
                    <label class="block text-sm font-semibold mb-1">Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <button onclick="setPaymentMethod('cash')" id="btnCash"
                            class="p-2 bg-green-500 text-white text-center rounded-md">CASH</button>
                        <button onclick="setPaymentMethod('non_cash')" id="btnNonCash"
                            class="p-2 bg-gray-200 text-center rounded-md">NON CASH</button>
                    </div>
                </div>

                {{-- Pilih uang cepat --}}
                <div id="selectMoney" class="mt-4 mb-4">
                    <label class="block text-sm font-semibold mb-1">Pilih Uang</label>
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <button onclick="addCash(2000)" class="p-2 bg-gray-200 text-center rounded-lg">Rp 2.000</button>
                        <button onclick="addCash(5000)" class="p-2 bg-gray-200 text-center rounded-lg">Rp 5.000</button>
                        <button onclick="addCash(10000)" class="p-2 bg-gray-200 text-center rounded-lg">Rp 10.000</button>
                        <button onclick="addCash(20000)" class="p-2 bg-gray-200 text-center rounded-lg">Rp 20.000</button>
                        <button onclick="addCash(50000)" class="p-2 bg-gray-200 text-center rounded-lg">Rp 50.000</button>
                        <button onclick="addCash(100000)" class="p-2 bg-gray-200 text-center rounded-lg">Rp 100.000</button>
                    </div>
                </div>

                {{-- Input uang tunai --}}
                <div id="moneyInput" class="mt-4">
                    <label class="block text-sm font-semibold mb-1" for="cashInput">Uang Tunai</label>
                    <input type="text" id="cashInput" placeholder="Rp " class="w-full p-2 border rounded" />
                </div>

                {{-- Kembalian --}}
                <div id="remainingMoney" class="mt-2 text-sm font-semibold">
                    <div class="flex justify-between">
                        <span>Kembalian</span>
                        <span id="changeAmount">Rp 0</span>
                    </div>
                </div>

                {{-- Tombol Bayar --}}
                <button onclick="checkout()" class="w-full bg-green-600 text-white font-bold py-3 mt-4 rounded-xl">
                    Bayar <span id="payAmount">Rp 0</span>
                </button>
            </div>
        </div>

        {{-- Dialog Print --}}
        <div id="printDialog" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-xl w-3/4 max-w-2xl">
                <h2 class="text-xl font-bold mb-4">Nota</h2>

                @include('dashboard.cashier._partials.spinner')

                <iframe id="receiptPreview" src="" width="100%" height="400px" class="hidden"></iframe>

                <div class="mt-4 flex justify-between">
                    <button class="bg-red-500 text-white px-4 py-2 rounded-xl" onclick="closePrintDialog()">Close</button>
                    <button class="bg-green-500 text-white px-4 py-2 rounded-xl" onclick="printReceipt()">Print</button>
                </div>
            </div>
        </div>

        {{-- Dialog Konfirmasi Non Cash --}}
        <div id="nonCashConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
            <div class="bg-white p-6 rounded-xl w-full max-w-md shadow-lg">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Konfirmasi Pembayaran</h3>
                <p class="text-sm text-gray-700">
                    Lanjutkan pembayaran NON CASH sebesar
                    <span id="nonCashConfirmAmount" class="font-semibold">Rp 0</span>?
                </p>
                <div class="mt-5 flex justify-end gap-3">
                    <button id="nonCashCancelBtn" type="button"
                        class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">Batal</button>
                    <button id="nonCashOkBtn" type="button"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Lanjut</button>
                </div>
            </div>
        </div>

        {{-- Dialog Detail Produk --}}
        <div id="productInfoModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
            <div class="bg-white p-6 rounded-xl w-full max-w-md shadow-lg">
                <h3 id="productInfoName" class="text-lg font-bold text-gray-800 mb-2">Detail Produk</h3>
                <p id="productInfoDescription" class="text-sm text-gray-700 leading-relaxed">-</p>
                <div class="mt-4 text-sm text-gray-700 space-y-1">
                    <p>Harga: <span id="productInfoPrice" class="font-semibold">-</span></p>
                    <p>Stok: <span id="productInfoStock" class="font-semibold">-</span></p>
                    <p>Kategori: <span id="productInfoCategory" class="font-semibold">-</span></p>
                    <p>Expired: <span id="productInfoExpiry" class="font-semibold">-</span></p>
                </div>
                <div class="mt-5 flex justify-end">
                    <button id="productInfoCloseBtn" type="button"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Overlay Loading --}}
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <svg class="animate-spin h-8 w-8 text-green-500 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <p class="text-sm font-semibold">Memproses pembayaran...</p>
        </div>
    </div>

    {{-- Include JS --}}
    @include('dashboard.cashier._partials.js_partials')

    @if ($midtransClientKey)
        @push('scripts')
            <script
                src="{{ $isMidtransProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
                data-client-key="{{ $midtransClientKey }}"></script>
        @endpush
    @endif

</x-layout>
