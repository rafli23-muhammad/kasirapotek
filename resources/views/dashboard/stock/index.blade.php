<x-layout>
  <div class="p-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-black font-bold text-2xl mb-0">Stok Obat</h1>
        <a href="{{ route('pembelian') }}"
            class="inline-flex w-fit items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            <i class="fa-solid fa-clock-rotate-left mr-2"></i>
            Riwayat pembelian / stok masuk
        </a>
    </div>

    @include('dashboard.stock._partials.table')

    @foreach ($products as $product)
      @include('dashboard.stock._partials.edit-modal')
    @endforeach
  </div>
</x-layout>