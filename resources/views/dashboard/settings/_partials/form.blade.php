<form class="max-w-lg w-full" action="{{ route('settings.update') }}" method="POST" multipart enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="mb-4">
      <label for="shop_name" class="block mb-2 text-sm font-medium text-gray-900">Nama Toko</label>
      <input type="text" id="shop_name" name="shop_name" value="{{ old('shop_name', $settings->shop_name ?? '') }}"
          class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500">
  </div>

  <div class="mb-4">
      <label for="address" class="block mb-2 text-sm font-medium text-gray-900">Alamat Toko</label>
      <input type="text" id="address" name="address" value="{{ old('address', $settings->address ?? '') }}"
          class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500">
  </div>

  <div class="mb-4">
      <label for="logo" class="block mb-2 text-sm font-medium text-gray-900">Logo Toko</label>
      <input type="file" id="logo" name="logo" accept="image/*"
          class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500">
      <div id="logo-preview" class="mt-4"></div>
  </div>

  <div class="mb-4">
      <label for="tax_percentage" class="block mb-2 text-sm font-medium text-gray-900">Persentase Pajak</label>
      <input type="number" id="tax_percentage" name="tax_percentage" value="{{ old('tax_percentage', $settings->tax_percentage ?? 0) }}"
          class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500">
  </div>

  <div class="mb-4">
      <!-- ATRIBUT ID, NAME, dan VALUE DI SINI SUDAH DIPERBAIKI -->
      <label for="default_discount" class="block mb-2 text-sm font-medium text-gray-900">Diskon Default</label>
      <input type="number" id="default_discount" name="default_discount" value="{{ old('default_discount', $settings->default_discount ?? 0) }}"
          class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500">
  </div>
  
  <div class="mb-4">
      <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Simpan Pengaturan</button>
  </div>
</form>

<script>
  const logoInput = document.getElementById('logo');
  const logoPreview = document.getElementById('logo-preview');

  window.onload = function() {
      const savedLogo = localStorage.getItem('logoImage');
      if (savedLogo) {
          logoPreview.innerHTML = `<img src="${savedLogo}" alt="Logo Preview" class="w-32 h-32 object-cover rounded-lg">`;
      }
  };

  logoInput.addEventListener('change', function(event) {
      const file = event.target.files[0];
      if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
              localStorage.setItem('logoImage', e.target.result);
              logoPreview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview" class="w-32 h-32 object-cover rounded-lg">`;
          };
          reader.readAsDataURL(file);
      }
  });
</script>
