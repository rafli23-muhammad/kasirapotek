<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('dashboard.user_setting.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user->email === 'kasir@gmail.com') {
            return redirect()
                ->route('profile')
                ->with('toast_error', 'Akun kasir@gmail.com hanya bisa diubah oleh admin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update nama pengguna
        $user->name = $request->name;

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Perbarui session nama pengguna agar langsung berubah di UI
        session(['name' => $user->name]);

        return redirect()
            ->route('profile')
            ->with('toast_success', 'Pengaturan akun berhasil diperbarui.');
    }
}
