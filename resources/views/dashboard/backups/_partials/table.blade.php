<div class="max-w-full overflow-x-auto">
  <table class="w-full table-auto">
      <thead>
          <tr class="bg-gray-400 text-left">
              <th class="min-w-[50px] px-4 py-4 font-medium text-black">No</th>
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">Backup pada tanggal</th>
              <th class="px-4 py-4 font-medium text-black">Aksi</th>
          </tr>
      </thead>
      <tbody>
          @forelse ($backups as $backup)
              <tr>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ ($backups->currentPage() - 1) * $backups->perPage() + $loop->iteration }}</p>
                  </td>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ $backup->created_at }}</p>
                  </td>
                <td class="border-b px-4 py-5">
                  <a href="{{ route('backup.download', $backup->id) }}" class="hover:text-primary">
                    <i class="fa-solid fa-download text-blue-500 bg-gray-300 hover:bg-gray-700 p-4 rounded-full"></i>
                  </a>
              </td>
              </tr>
          @empty
              <tr>
                  <td colspan="4" class="text-center px-4 py-5 text-gray-500">
                      Tidak ada Backup.
                  </td>
              </tr>
          @endforelse
      </tbody>
  </table>
</div>

<div class="mt-4">
  {{ $backups->links() }}
</div>
