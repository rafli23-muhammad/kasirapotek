<div class="max-w-full overflow-x-auto">
  <table class="w-full table-auto">
      <thead>
          <tr class="bg-gray-400 text-left">
              <th class="min-w-[50px] px-4 py-4 font-medium text-black">No</th>
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">Nama Product</th>
              <th class="min-w-[150px] px-4 py-4 font-medium text-black">
                <div class="flex items-center space-x-2">
                  <span>Terjual</span>
                  <form method="GET" action="{{ route('report') }}">
                      <input type="hidden" name="sort"
                          value="{{ request('sort') === 'asc' ? 'desc' : 'asc' }}">
                      <button type="submit" class="text-gray-600 hover:text-black">
                          @if (request('sort') === 'asc')
                              <i class="fa-solid fa-arrow-up-wide-short"></i>
                          @else
                              <i class="fa-solid fa-arrow-down-wide-short"></i>
                          @endif
                      </button>
                  </form>
              </div>
              </th>
          </tr>
      </thead>
      <tbody>
          @forelse ($products as $product)
              <tr>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ $loop->iteration }}</p>
                  </td>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ $product->name }}</p>
                  </td>
                  <td class="border-b px-4 py-5">
                      <p class="text-black">{{ $itemsSold[$product->id] ?? 0 }} terjual</p>
                  </td>
              </tr>
          @empty
              <tr>
                  <td colspan="6" class="text-center px-4 py-5 text-gray-500">
                      Tidak ada Produk.
                  </td>
              </tr>
          @endforelse
      </tbody>
  </table>
</div>

