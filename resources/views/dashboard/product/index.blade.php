<x-layout>
  <div class="p-8">
    <div class="flex justify-between">
        <h1 class="text-black font-bold text-2xl mb-4">Data Obat</h1>
        <button data-modal-target="add-product-modal" data-modal-toggle="add-product-modal" type="button"
            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
            Tambah Obat
        </button>
    </div>

    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
      <p class="text-yellow-900 font-semibold">Notifikasi Kedaluwarsa</p>
      <p class="text-sm text-yellow-800">
        Obat expired: <span class="font-semibold">{{ $expiredCount }}</span> |
        Hampir expired (<= 30 hari): <span class="font-semibold">{{ $nearExpiredCount }}</span>
      </p>
    </div>

    <div class="flex flex-wrap gap-2 mb-4">
      <a href="{{ route('product', ['expiry_status' => 'all']) }}"
        class="px-4 py-2 rounded-lg text-sm {{ $expiryStatus === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
        Semua
      </a>
      <a href="{{ route('product', ['expiry_status' => 'near_expired']) }}"
        class="px-4 py-2 rounded-lg text-sm {{ $expiryStatus === 'near_expired' ? 'bg-amber-500 text-white' : 'bg-gray-200 text-gray-700' }}">
        Hampir Expired
      </a>
      <a href="{{ route('product', ['expiry_status' => 'expired']) }}"
        class="px-4 py-2 rounded-lg text-sm {{ $expiryStatus === 'expired' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700' }}">
        Expired
      </a>
      <a href="{{ route('product.print-expired') }}" target="_blank"
        class="px-4 py-2 rounded-lg text-sm bg-indigo-600 text-white hover:bg-indigo-700">
        Cetak Dokumen Expired
      </a>
    </div>

    @include('dashboard.product._partials.table')

    @include('dashboard.product._partials.add-modal')
    @foreach ($products as $product)
      @include('dashboard.product._partials.edit-modal')
    @endforeach

  </div>
</x-layout>