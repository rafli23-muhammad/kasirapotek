<x-layout>
  <div class="p-8">
    <div class="flex justify-between">
        <h1 class="text-black font-bold text-2xl mb-4">Backup Database</h1>
    </div>

    <div class="flex justify-between">
      <button data-modal-target="restore-modal" data-modal-toggle="restore-modal" type="button"
            class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
            Restore Database
        </button>
      <button data-modal-target="backup-modal" data-modal-toggle="backup-modal" type="button"
            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
            Lakukan Backup
        </button>
    </div>

    @include('dashboard.backups._partials.table')
    @include('dashboard.backups._partials.restore-modal')

    @include('dashboard.backups._partials.backup-modal')


  </div>
</x-layout>