<div class="max-w-full overflow-x-auto">
  <table class="w-full table-auto">
      <thead>
          <tr class="bg-gray-400 text-left">
              <th class="min-w-[50px] px-4 py-4 font-medium text-black">No</th>
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">Dilakukan oleh</th>
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">Status</th>
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">Stock</th>
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">Deskripsi</th>
          </tr>
      </thead>
      <tbody>
          @forelse ($logStocks as $log)
              <tr>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ ($logStocks->currentPage() - 1) * $logStocks->perPage() + $loop->iteration }}</p>
                  </td>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ $log->user->name }}</p>
                  </td>
                  <td class="border-b px-4 py-5">
                    @php
                        $statusClass = match ($log->type) {
                            'out' => 'bg-red-100 text-red-700',
                            'in' => 'bg-green-100 text-green-700',
                            default => 'bg-gray-200 text-gray-700',
                        };

                        $statusText = match ($log->type) {
                            'out' => 'Stok Keluar',
                            'in' => 'Stok Masuk',
                            default => 'Unknown',
                        };
                        @endphp
                      <p class="inline-flex rounded-full px-3 py-1 text-sm font-medium {{ $statusClass }}">{{ $statusText }}</p>
                  </td>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ $log->stock }}</p>
                  </td>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ $log->description }}</p>
                  </td>
              </tr>
          @empty
              <tr>
                  <td colspan="5" class="text-center px-4 py-5 text-gray-500">
                      Tidak ada Log.
                  </td>
              </tr>
          @endforelse
      </tbody>
  </table>
</div>

<div class="mt-4">
  {{ $logStocks->links() }}
</div>
