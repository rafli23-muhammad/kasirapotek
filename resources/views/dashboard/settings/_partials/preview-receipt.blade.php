<div class="flex justify-center items-center">
  <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-3xl">
      {{-- ======= Header Toko ======= --}}
      <div class="text-center mb-4">
          @if (!empty($settings->logo))
              <img
                  src="{{ asset('storage/' . $settings->logo) }}"
                  alt="Logo Toko"
                  class="h-14 w-14 object-contain mx-auto mb-2"
              />
          @endif
          <h2 class="text-xl font-semibold">
              {{ $settings->shop_name ?? 'Nama Toko' }}
          </h2>
          <p>{{ $settings->address ?? 'Alamat Toko Belum Diatur' }}</p>
      </div>

      <div class="flex mt-4 text-xs">
          <div class="flex-grow">No: 12345</div>
          <div>{{ now()->format('d-m-Y') }}</div>
      </div>

      <hr class="my-2 border-t border-gray-300">

      {{-- ======= Daftar Item ======= --}}
      <div>
          <table class="w-full table-auto border-collapse">
              <thead>
                  <tr>
                      <th class="py-1 text-left">#</th>
                      <th class="py-1 text-left">Item</th>
                      <th class="py-1 text-center">Qty</th>
                      <th class="py-1 text-right">Subtotal</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td class="py-2 text-center">1</td>
                      <td class="py-2 text-left">
                          Item Name<br />
                          <small>Rp 10.000</small>
                      </td>
                      <td class="py-2 text-center">2</td>
                      <td class="py-2 text-right" id="subtotal1">Rp 20.000</td>
                  </tr>
                  <tr>
                      <td class="py-2 text-center">2</td>
                      <td class="py-2 text-left">
                          Another Item<br />
                          <small>Rp 15.000</small>
                      </td>
                      <td class="py-2 text-center">1</td>
                      <td class="py-2 text-right" id="subtotal2">Rp 15.000</td>
                  </tr>
              </tbody>
          </table>
      </div>

      <hr class="my-2 border-t border-gray-300">

      {{-- ======= Ringkasan Total ======= --}}
      <div>
          <div class="flex font-semibold">
              <div class="flex-grow">TOTAL</div>
              <div id="total">Rp 35.000</div>
          </div>
          <div class="flex text-xs font-semibold">
              <div class="flex-grow">Pajak ({{ $settings->tax_percentage ?? 0 }}%)</div>
              <div id="tax">Rp 3.500</div>
          </div>
          <div class="flex text-xs font-semibold">
              <div class="flex-grow">Diskon ({{ $settings->default_discount ?? 0 }}%)</div>
              <div id="discount">Rp 1.750</div>
          </div>

          <hr class="my-2 border-t border-gray-300">

          <div class="flex font-semibold">
              <div class="flex-grow">Total Harga</div>
              <div id="total-price">Rp 36.750</div>
          </div>
      </div>
  </div>
</div>

{{-- ======= SCRIPT PERHITUNGAN ======= --}}
<script>
  // Subtotal contoh (bisa diganti dari backend)
  const subtotal1 = 20000;
  const subtotal2 = 15000;

  // Ambil nilai dari backend secara aman
  const discountPercentage = {{ $settings->default_discount ?? 0 }};
  const taxPercentage = {{ $settings->tax_percentage ?? 0 }};
  
  // Hitung total, pajak, diskon, dan total akhir
  const total = subtotal1 + subtotal2;
  const discount = (total * discountPercentage) / 100;
  const tax = (total * taxPercentage) / 100;
  const totalPrice = total + tax - discount;

  // Format angka ke Rupiah
  const formatRupiah = (num) => 'Rp ' + num.toLocaleString('id-ID');

  // Tampilkan hasil ke HTML
  document.getElementById('subtotal1').innerText = formatRupiah(subtotal1);
  document.getElementById('subtotal2').innerText = formatRupiah(subtotal2);
  document.getElementById('total').innerText = formatRupiah(total);
  document.getElementById('tax').innerText = formatRupiah(tax);
  document.getElementById('discount').innerText = formatRupiah(discount);
  document.getElementById('total-price').innerText = formatRupiah(totalPrice);
</script>
