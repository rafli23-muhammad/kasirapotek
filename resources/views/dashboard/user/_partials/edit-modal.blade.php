<div id="edit-user-modal-{{ $user->id }}" class="pt-12 hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-screen bg-black bg-opacity-50 backdrop-blur-sm">
  <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="bg-white rounded-lg shadow-sm overflow-y-auto max-h-[80vh]">
          <div class="flex items-center justify-between p-4 border-b">
              <h3 class="text-lg font-semibold text-gray-900">Edit User</h3>
              <button type="button" class="text-gray-400 hover:text-gray-900" data-modal-toggle="edit-user-modal-{{ $user->id }}">&times;</button>
          </div>
          <form method="POST" action="{{ route('user.update', $user->id) }}" class="p-4">
              @csrf
              @method('PUT')
              <div class="grid gap-4 mb-4 grid-cols-2">
                  <div class="col-span-2">
                      <label for="edit-name">Nama User</label>
                      <input type="text" name="name" id="edit-name" required class="w-full rounded-lg p-2.5 bg-gray-50" value="{{ $user->name }}">
                  </div>
                  <div class="col-span-2">
                      <label for="edit-email">Email</label>
                      <input type="email" name="email" id="edit-email" required class="w-full rounded-lg p-2.5 bg-gray-50" value="{{ $user->email }}">
                  </div>
                  <div class="col-span-2 relative">
                      <label for="edit-password">Password (Kosongkan jika tidak diubah)</label>
                      <input type="password" name="password" id="edit-password" class="w-full rounded-lg p-2.5 bg-gray-50 pr-10">
                      <span toggle="#edit-password" class="absolute right-3 top-10 cursor-pointer toggle-password text-gray-500">
                          <i class="fas fa-eye-slash"></i>
                      </span>
                  </div>
                  
                  <div class="col-span-2 relative">
                      <label for="edit-password_confirmation">Konfirmasi Password</label>
                      <input type="password" name="password_confirmation" id="edit-password_confirmation" class="w-full rounded-lg p-2.5 bg-gray-50 pr-10">
                      <span toggle="#edit-password_confirmation" class="absolute right-3 top-10 cursor-pointer toggle-password text-gray-500">
                          <i class="fas fa-eye-slash"></i>
                      </span>
                  </div>

                  <div class="col-span-2">
                      <label for="edit-role">Role</label>
                      <select name="role" id="edit-role" required class="w-full rounded-lg p-2.5 bg-gray-50">
                          <option value="">-- Pilih Role --</option>
                          <option value="Admin" {{ $user->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                          <option value="Cashier" {{ $user->role == 'Cashier' ? 'selected' : '' }}>Kasir</option>
                      </select>
                  </div>
              </div>
              <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-lg px-5 py-2.5">Update</button>
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
