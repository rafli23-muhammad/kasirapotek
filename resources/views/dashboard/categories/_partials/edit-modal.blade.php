<!-- Modal Edit Kategori -->
<div id="edit-category-modal-{{ $category->id }}" class="pt-12 hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-screen bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="bg-white rounded-lg shadow-sm overflow-y-auto max-h-[80vh]">
  
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Edit Kategori</h3>
                <button type="button" class="text-gray-400 hover:text-gray-900" data-modal-toggle="edit-category-modal-{{ $category->id }}">&times;</button>
            </div>
  
            <!-- Modal Body -->
            <form action="{{ route('category.update', $category->id) }}" method="POST" class="p-4">
                @csrf
                @method('PUT')
                <div class="grid gap-4 mb-4">
                    <div class="col-span-2">
                        <label for="name_{{ $category->id }}" class="block mb-1 text-sm font-medium text-gray-900">Nama Kategori</label>
                        <input type="text" name="name" id="name_{{ $category->id }}" required
                            value="{{ $category->name }}"
                            class="w-full rounded-lg p-2.5 bg-gray-50 text-gray-900">
                    </div>
                </div>
  
                <button type="submit"
                    class="w-full bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-lg px-5 py-2.5">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
  </div>
  