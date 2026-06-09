<x-layout>
  <div class="p-8">
    <div class="flex justify-between mb-2">
        <h1 class="text-black font-bold text-2xl">Log Stock dari Produk {{ $product->name }}</h1>

    </div>

    @include('dashboard.log_stock._partials.table')
  </div>
</x-layout>