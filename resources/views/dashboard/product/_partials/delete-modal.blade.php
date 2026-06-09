<div id="product-delete-modal-{{ $product->id }}" tabindex="-1"
  class="hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full h-full">
  <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="bg-white rounded-lg shadow-sm">
          <div class="p-4 text-center">
              <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" fill="none" viewBox="0 0 20 20">
                  <path stroke="currentColor" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
              </svg>
              <h3 class="mb-5 text-lg font-normal text-gray-500">Apakah anda yakin ingin menghapus Produk ini?</h3>
              <form method="POST" action="{{ route('product.destroy', $product->id) }}">
                @csrf
                @method('DELETE')
                  <button type="submit" class="text-white bg-red-600 hover:bg-red-800 px-5 py-2.5 rounded-lg">Ya, saya yakin</button>
                  <button type="button" data-modal-hide="product-delete-modal-{{ $product->id }}" class="px-5 py-2.5 ms-3 border rounded-lg">Batalkan</button>
              </form>
          </div>
      </div>
  </div>
</div>