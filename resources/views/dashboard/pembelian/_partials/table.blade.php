<div class="max-w-full overflow-x-auto">
  <table class="w-full table-auto">
    <thead>
      <tr class="bg-gray-400 text-left">
        <th class="min-w-[50px] px-4 py-4 font-medium text-black">No</th>
        <th class="min-w-[160px] px-4 py-4 font-medium text-black">Tanggal</th>
        <th class="min-w-[180px] px-4 py-4 font-medium text-black">Obat</th>
        <th class="min-w-[100px] px-4 py-4 font-medium text-black">Jumlah</th>
        <th class="min-w-[150px] px-4 py-4 font-medium text-black">Oleh</th>
        <th class="min-w-[220px] px-4 py-4 font-medium text-black">Keterangan</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($logs as $log)
        <tr>
          <td class="border-b px-4 py-5">
            <p class="text-black">{{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}</p>
          </td>
          <td class="border-b px-4 py-5">
            <p class="text-black">{{ $log->created_at->locale('id')->translatedFormat('j M Y, H:i') }}</p>
          </td>
          <td class="border-b px-4 py-5">
            <p class="text-black">{{ $log->product->name ?? '—' }}</p>
          </td>
          <td class="border-b px-4 py-5">
            <p class="text-black">{{ $log->stock }}</p>
          </td>
          <td class="border-b px-4 py-5">
            <p class="text-black">{{ $log->user->name ?? '—' }}</p>
          </td>
          <td class="border-b px-4 py-5">
            <p class="text-black">{{ $log->description ?? '—' }}</p>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="text-center px-4 py-5 text-gray-500">
            Belum ada riwayat pembelian / penambahan stok.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">
  {{ $logs->links() }}
</div>
