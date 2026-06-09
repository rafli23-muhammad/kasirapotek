<x-layout>
  <div class="p-8">
    <div class="flex justify-between items-center">
        <h1 class="text-black font-bold text-2xl mb-4">Laporan Penjualan</h1>
    </div>

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
      <form method="GET" action="{{ route('report.sales') }}" class="flex items-center gap-3">
          <div class="relative">
              <input
                  type="text"
                  name="date_range"
                  id="date_range"
                  class="w-[240px] border border-gray-300 rounded-lg pl-10 pr-6 py-2 text-sm text-black shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Pilih Rentang Tanggal"
                  value="{{ request('date_range') }}"
                  autocomplete="off"
              >
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                  <i class="fa-solid fa-calendar-days"></i>
              </div>
          </div>
          <button
              type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow"
          >
              Filter
          </button>
      </form>

      <div>
          <h1 class="text-black font-semibold text-sm md:text-lg">
              Tanggal {{ $dateText }}
          </h1>
      </div>
    </div>

    <div class="flex justify-start gap-3 my-4">
      <a href="{{ route('report.pdf.sales', ['date_range' => request('date_range')]) }}"
         class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow inline-flex items-center gap-2">
          <i class="fa-solid fa-file-pdf"></i> Export PDF
      </a>
      <a href="{{ route('report.excel.sales', ['date_range' => request('date_range')]) }}"
         class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow inline-flex items-center gap-2">
          <i class="ri-file-excel-2-line"></i> Export Excel
      </a>
      <a href="{{ route('report') }}"
         class="bg-yellow-400 hover:bg-yellow-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow inline-flex items-center gap-2">
          <i class="fa-solid fa-eye"></i> Laporan Produk
      </a>
    </div>

    <div class="flex justify-start my-3 gap-3 text-black">
      <h1>Laba Bersih : {{ rupiah($netProfit) }}</h1>
      <h1>Laba kotor : {{ rupiah($profit) }}</h1>
    </div>
    

    @include('dashboard.report._partials.table-sales')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script>
    flatpickr("#date_range", {
      mode: "range",
      dateFormat: "Y-m-d",
      defaultDate: "{{ request('date_range') }}"
    });
  </script>
</x-layout>
