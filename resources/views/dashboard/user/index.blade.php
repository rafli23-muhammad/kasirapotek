<x-layout>
  <div class="p-8">
    
    <div class="flex justify-between items-center mb-4">
      <h1 class="font-bold text-black text-2xl">Manajemen User</h1>
      <button
        data-modal-target="add-user-modal"
        data-modal-toggle="add-user-modal"
        type="button"
        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">
        Tambah User
      </button>
    </div>

    @include('dashboard.user._partials.table')

    @include('dashboard.user._partials.add-modal')

    @foreach ($users as $user)
      @include('dashboard.user._partials.edit-modal')
    @endforeach

  </div>
</x-layout>