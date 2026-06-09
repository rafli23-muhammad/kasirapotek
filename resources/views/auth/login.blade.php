<x-layout>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
      <div class="flex justify-center">
        <img src="{{ asset('logo.png') }}" alt="Logo" class="h-20 w-20">
      </div>
      <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center flex items-center justify-center space-x-2">
        <span>Login ke Dashboard Kasir</span>
      </h2>
      
      <form class="space-y-4" action="{{ route('login.post') }}" method="POST">
        @csrf
  
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input 
            name="email"
            type="email" 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition-all"
            placeholder="Masukan email anda"
            autocomplete="off"
          />
        </div>
  
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input 
            name="password"
            type="password" 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition-all"
            placeholder="••••••••"
            autocomplete="off"
          />
        </div>
  
        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-700 text-white font-medium py-2.5 rounded-lg transition-colors">
          Masuk
        </button>
      </form>
    </div>
  </div>
  </x-layout>
  