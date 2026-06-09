<x-layout>
  <div class="p-8">
      <div class="flex justify-between mb-4">
          <h1 class="text-black font-bold text-2xl">Pengaturan Toko</h1>
      </div>

      <div class="flex flex-col sm:flex-row justify-between">
          @include('dashboard.settings._partials.form')

          <div class="flex flex-col sm:w-1/3 mt-8 sm:mt-0 text-black">
              @include('dashboard.settings._partials.preview-receipt')

              <div class="p-4 w-full">
                <button 
                  class="bg-green-500 text-white text-lg px-4 py-3 rounded-2xl w-full focus:outline-none" 
                  onclick="openPrintDialog()">Test Print</button>
              </div>
          </div>
      </div>
  </div>

  <div id="printDialog" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-xl w-3/4 max-w-2xl">
      <h2 class="text-xl font-bold mb-4">Preview Receipt</h2>

      @include('dashboard.settings._partials.spinner')

      <iframe id="receiptPreview" src="" width="100%" height="500px" class="hidden"></iframe>

      <div class="mt-4 flex justify-between">
        <button class="bg-red-500 text-white px-4 py-2 rounded-xl" onclick="closePrintDialog()">Close</button>
        <button class="bg-green-500 text-white px-4 py-2 rounded-xl" onclick="printReceipt()">Print</button>
      </div>
    </div>
  </div>

  <script>
    function openPrintDialog() {
      document.getElementById('loadingSpinner').classList.remove('hidden');
      document.getElementById('receiptPreview').classList.add('hidden');

      const pdfUrl = '{{ route('settings.receipt-pdf') }}';
      
      const iframe = document.getElementById('receiptPreview');
      iframe.onload = function() {
        document.getElementById('loadingSpinner').classList.add('hidden');
        iframe.classList.remove('hidden');
      };
      iframe.src = pdfUrl;

      document.getElementById('printDialog').classList.remove('hidden');
    }

    function closePrintDialog() {
      document.getElementById('printDialog').classList.add('hidden');
    }

    function printReceipt() {
      const iframe = document.getElementById('receiptPreview');
      iframe.contentWindow.print();
    }
  </script>
</x-layout>
