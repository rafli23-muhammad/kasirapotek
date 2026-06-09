<div id="add-user-modal" class="pt-12 hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-screen bg-black bg-opacity-50 backdrop-blur-sm">
  <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="bg-white rounded-lg shadow-sm overflow-y-auto max-h-[80vh]">
          <div class="flex items-center justify-between p-4 border-b ">
              <h3 class="text-lg font-semibold text-gray-900">Tambah User</h3>
              <button type="button" class="text-gray-400 hover:text-gray-900" data-modal-toggle="add-user-modal">&times;</button>
          </div>
          <form method="POST" action="{{ route('user.store') }}" class="p-4">
              @csrf
              <div class="grid gap-4 mb-4 grid-cols-2">
                  <div class="col-span-2">
                      <label for="name">Nama User</label>
                      <input type="text" name="name" id="name" required class="w-full rounded-lg p-2.5 bg-gray-50">
                  </div>
                  <div class="col-span-2">
                      <label for="email">Email</label>
                      <input type="email" name="email" id="email" required class="w-full rounded-lg p-2.5 bg-gray-50">
                  </div>
                  <div class="col-span-2 relative">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required class="w-full rounded-lg p-2.5 bg-gray-50 pr-10">
                    <span toggle="#password" class="absolute right-3 top-10 cursor-pointer toggle-password text-gray-500">
                        <i class="fas fa-eye-slash"></i>
                    </span>
                </div>
                
                <div class="col-span-2 relative">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full rounded-lg p-2.5 bg-gray-50 pr-10">
                    <span toggle="#password_confirmation" class="absolute right-3 top-10 cursor-pointer toggle-password text-gray-500">
                        <i class="fas fa-eye-slash"></i>
                    </span>
                </div>
                

                  <div class="col-span-2">
                      <label for="role">Role</label>
                      <select name="role" id="role" required class="w-full rounded-lg p-2.5 bg-gray-50">
                          <option value="">-- Pilih Role --</option>
                          <option value="Admin">Admin</option>
                          <option value="Cashier">Kasir</option>
                      </select>
                  </div>
              </div>
              <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-medium rounded-lg px-5 py-2.5">Simpan</button>
          </form>
      </div>
  </div>
</div>

<script>
    document.querySelectorAll('.toggle-password').forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            const input = document.querySelector(this.getAttribute('toggle'));
            const icon = this.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    });
</script>
