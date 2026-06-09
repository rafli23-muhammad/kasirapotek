<x-layout>
  <div class="p-8">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-black font-bold text-2xl">Pembelian Obat</h1>
        <p class="mt-1 text-sm text-gray-600">
          Riwayat stok masuk. Untuk menambah stok obat setelah pembelian, gunakan halaman
          <a href="{{ route('stock') }}" class="font-medium text-green-700 underline hover:text-green-800">Stok Obat</a>.
        </p>
      </div>
      <a href="{{ route('stock') }}"
        class="inline-flex items-center justify-center rounded-lg bg-green-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300">
        <i class="fa-solid fa-boxes-stacked mr-2"></i>
        Kelola stok obat
      </a>
    </div>

    @include('dashboard.pembelian._partials.table')
  </div>
</x-layout>
