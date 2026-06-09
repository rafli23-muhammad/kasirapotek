<x-layout>
  <div class="p-8">
    <div class="flex justify-between">
        <h1 class="text-black font-bold text-2xl mb-4">Pengaturan Akun</h1>
    </div>

    <form action="{{ route('profile.update') }}" method="POST">
      @csrf
      @method('PUT')

      <div class="space-y-6">
          <!-- Name Field -->
          <div>
              <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
              <input 
                  type="text" 
                  id="name" 
                  name="name" 
                  value="{{ old('name', $user->name) }}" 
                  class="mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  placeholder="Masukkan nama lengkap"
              >
              @error('name')
                  <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
          </div>

          <div>
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input 
                  type="email" 
                  id="email" 
                  name="email" 
                  value="{{ old('email', $user->email) }}" 
                  class="mt-1 block w-full px-4 py-2 bg-gray-200 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  disabled
              >
          </div>

          <div>
              <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
              <input 
                  type="password" 
                  id="password" 
                  name="password" 
                  class="mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  placeholder="Minimal 8 karakter"
              >
              @error('password')
                  <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
          </div>

          <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
              <input 
                  type="password" 
                  id="password_confirmation" 
                  name="password_confirmation" 
                  class="mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              >
          </div>
      </div>

      <div class="mt-6">
          <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-medium text-sm rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-200">
              Simpan Perubahan
          </button>
      </div>
  </form>
  </div>
</x-layout>
