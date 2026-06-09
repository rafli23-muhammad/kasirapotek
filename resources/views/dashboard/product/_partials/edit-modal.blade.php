<div id="edit-product-modal-{{ $product->id }}" class="pt-12 hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-screen bg-black bg-opacity-50 backdrop-blur-sm">
  <div class="relative p-4 w-full max-w-md max-h-full">
    <div class="bg-white rounded-lg shadow-sm overflow-y-auto max-h-[80vh]">
      
      <div class="flex items-center justify-between p-4 border-b">
        <h3 class="text-lg font-semibold text-gray-900">Edit Produk</h3>
        <button type="button" class="text-gray-400 hover:text-gray-900" data-modal-toggle="edit-product-modal-{{ $product->id }}">&times;</button>
      </div>
      
      <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-4">
        @csrf
        @method('PUT')
        <div class="grid gap-4 mb-4">
          <div class="col-span-2">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-lg p-2.5 bg-gray-50">
          </div>

          <div class="col-span-2">
            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori Produk</label>
            <select name="category_id" id="category_id" required class="w-full rounded-lg p-2.5 bg-gray-50">
              <option value="">Pilih Kategori</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Produk</label>
            <textarea name="description" id="description" required class="w-full rounded-lg p-2.5 bg-gray-50" rows="4">{{ old('description', $product->description) }}</textarea>
          </div>

          <div class="col-span-2">
            <label for="purchase_price" class="block text-sm font-medium text-gray-700">Harga Beli</label>
            <input type="number" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" required class="w-full rounded-lg p-2.5 bg-gray-50" min="0">
          </div>

          <div class="col-span-2">
            <label for="selling_price" class="block text-sm font-medium text-gray-700">Harga Jual</label>
            <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price', $product->selling_price) }}" required class="w-full rounded-lg p-2.5 bg-gray-50" min="0">
          </div>

          <div class="col-span-2">
            <label for="stock" class="block text-sm font-medium text-gray-700">Stok Produk</label>
            <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required class="w-full rounded-lg p-2.5 bg-gray-50" min="0">
          </div>

          <div class="col-span-2">
            <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700">Batas Stok Rendah</label>
            <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" required class="w-full rounded-lg p-2.5 bg-gray-50" min="0">
          </div>

          <div class="col-span-2">
            <label for="expiry_date" class="block text-sm font-medium text-gray-700">Tanggal Kedaluwarsa</label>
            <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', optional($product->expiry_date)->format('Y-m-d')) }}" class="w-full rounded-lg p-2.5 bg-gray-50">
          </div>

          <div class="col-span-2">
            <label for="image" class="block text-sm font-medium text-gray-700">Gambar Produk</label>
            <input type="file" name="image" id="image" class="w-full rounded-lg p-2.5 bg-gray-50" onchange="previewImage(event)">
          </div>

          <div id="image-preview" class="col-span-2 mt-4">
            <p class="text-sm font-medium text-gray-700">Preview Gambar:</p>
            <img id="preview" class="w-48 h-48 object-cover rounded-md" src="{{ old('image', asset('storage/' . $product->image)) }}" alt="Image Preview" style="display: block;">
          </div>
        </div>

        <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-medium rounded-lg px-5 py-2.5">Simpan</button>
      </form>
    </div>
  </div>
</div>

<script>
  function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');

    if (file) {
      const reader = new FileReader();

      reader.onload = function(e) {
        localStorage.setItem('imagePreview', e.target.result);

        preview.src = e.target.result;
        preview.style.display = 'block';
      }

      reader.readAsDataURL(file);
    }
  }

  window.onload = function() {
    const storedImage = localStorage.getItem('imagePreview');
    const preview = document.getElementById('preview');
    
    if (storedImage) {
      preview.src = storedImage;
      preview.style.display = 'block';
    }
  }
</script>
