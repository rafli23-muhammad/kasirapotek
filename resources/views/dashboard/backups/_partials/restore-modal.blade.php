<div id="restore-modal" class="pt-12 hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-screen bg-black bg-opacity-50 backdrop-blur-sm">
  <div class="relative p-4 w-full max-w-md max-h-full">
    <div class="bg-white rounded-lg shadow-sm overflow-y-auto max-h-[80vh]">
      
      <div class="flex items-center justify-between p-4 border-b">
        <h3 class="text-lg font-semibold text-gray-900">Restore Database</h3>
        <button type="button" class="text-gray-400 hover:text-gray-900" data-modal-toggle="restore-modal">&times;</button>
      </div>
      
      <form action="{{ route('backup.restore') }}" method="POST" class="p-4">
        @csrf
        <div class="grid gap-4 mb-4">

          <div class="col-span-2">
            <label for="backup_id" class="block text-sm font-medium text-gray-700">Tanggal Backup</label>
            <select name="backup_id" id="backup_id" required class="w-full rounded-lg p-2.5 bg-gray-50">
              <option value="">Pilih Backup Database</option>
              @foreach($all_backups as $backup)
                <option value="{{ $backup->id }}">{{ $backup->created_at }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-lg px-5 py-2.5">Restore</button>
      </form>
    </div>
  </div>
</div>
