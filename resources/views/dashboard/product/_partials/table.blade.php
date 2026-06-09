<div class="max-w-full overflow-x-auto">
  <table class="w-full table-auto">
      <thead>
          <tr class="bg-gray-400 text-left">
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">No</th>
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">Kategori</th>
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">Nama</th>
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">Gambar</th>
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">Deskripsi</th>
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">Harga Beli</th>
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">Harga Jual</th>
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">Stok</th>
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">Batas Stok Sedikit</th>
              <th class="min-w-[170px] px-4 py-4 font-medium text-black">Tanggal Kedaluwarsa</th>
              <th class="min-w-[140px] px-4 py-4 font-medium text-black">Status Expired</th>
              <th class="px-4 py-4 font-medium text-black">Aksi</th>
          </tr>
      </thead>
      <tbody>
          @forelse ($products as $product)
              @php
                  $expiryDate = $product->expiry_date;
                  $isExpired = $expiryDate && $expiryDate->isPast();
                  $isNearExpired = $expiryDate && !$isExpired && $expiryDate->lte(\Carbon\Carbon::today()->addDays(30));
              @endphp
              <tr>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">
                          {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                      </p>
                  </td>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ $product->category->name }}</p>
                  </td>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ $product->name }}</p>
                  </td>
                  <td class="border-b px-4 py-5">
                      @if ($product->image)
                          <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                              class="w-24 h-24 object-fit rounded-md">
                      @else
                          <p class="text-gray-500">Tidak ada gambar</p>
                      @endif
                  </td>                
                  <td class="border-b px-4 py-5">
                    <p class="text-black truncate max-w-[200px] cursor-pointer hover:text-blue-500"
                        data-modal-target="desc-modal-{{ $product->id }}"
                        data-modal-toggle="desc-modal-{{ $product->id }}">
                        {{ text_limiter($product->description) }}
                    </p>
                </td>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ rupiah($product->purchase_price) }}</p>
                  </td>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ rupiah($product->selling_price) }}</p>
                  </td>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ $product->stock }}</p>
                  </td>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ $product->low_stock_threshold }}</p>
                  </td>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ $expiryDate ? $expiryDate->format('d-m-Y') : '-' }}</p>
                  </td>
                  <td class="border-b px-4 py-5">
                      @if ($isExpired)
                          <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">Expired</span>
                      @elseif ($isNearExpired)
                          <span class="px-2 py-1 rounded-full text-xs bg-amber-100 text-amber-700">Hampir Expired</span>
                      @else
                          <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Aman</span>
                      @endif
                  </td>
                  <td class="border-b px-4 py-5">
                      <div class="flex items-center space-x-3.5">
                          <button data-modal-target="edit-product-modal-{{ $product->id }}"
                              data-modal-toggle="edit-product-modal-{{ $product->id }}" class="hover:text-primary">
                              <i class="fa-solid fa-pencil text-blue-500"></i>
                          </button>
                          <button data-modal-target="product-delete-modal-{{ $product->id }}"
                              data-modal-toggle="product-delete-modal-{{ $product->id }}"
                              class="hover:text-primary">
                              <i class="fas fa-trash-alt text-red-500"></i>
                          </button>
                      </div>
                  </td>
              </tr>
              <div id="desc-modal-{{ $product->id }}" tabindex="-1"
                  class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                  <div class="relative w-full max-w-md max-h-full">
                      <div class="relative bg-white rounded-lg shadow">
                          <div class="flex items-start justify-between p-4 border-b rounded-t">
                              <h3 class="text-xl font-semibold text-gray-900">
                                  Deskripsi Produk
                              </h3>
                              <button type="button"
                                  class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                  data-modal-hide="desc-modal-{{ $product->id }}">
                                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                      <path fill-rule="evenodd"
                                          d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 1 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414z"
                                          clip-rule="evenodd" />
                                  </svg>
                              </button>
                          </div>
                          <div class="p-6 space-y-6">
                              <p class="text-base leading-relaxed text-gray-500">
                                  {{ $product->description }}
                              </p>
                          </div>
                      </div>
                  </div>
              </div>
              @include('dashboard.product._partials.delete-modal', ['product' => $product])
          @empty
              <tr>
                  <td colspan="12" class="text-center px-4 py-5 text-gray-500">
                      Tidak ada Produk.
                  </td>
              </tr>
          @endforelse
      </tbody>
  </table>
</div>

<div class="mt-4">
  {{ $products->links() }}
</div>
