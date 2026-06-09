<div id="edit-stock-modal-{{ $product->id }}"
    class="pt-12 hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-screen bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="bg-white rounded-lg shadow-sm overflow-y-auto max-h-[80vh]">

            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Edit Kategori</h3>
                <button type="button" class="text-gray-400 hover:text-gray-900"
                    data-modal-toggle="edit-stock-modal-{{ $product->id }}">&times;</button>
            </div>

            <form action="{{ route('stock.update', $product->id) }}" method="POST" class="p-4">
                @csrf
                @method('PUT')
                <div class="grid gap-4 mb-4">
                    <div class="col-span-2">
                        <label for="stock" class="block text-sm font-medium text-gray-700">Stok Produk</label>
                        <input type="number" name="stock" id="stock-{{ $product->id }}" value="{{ old('stock', $product->stock) }}" required class="w-full rounded-lg p-2.5 bg-gray-50" min="0">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-lg px-5 py-2.5">
                    Simpan Perubahan
                </button>

            </form>
        </div>
    </div>
</div>

