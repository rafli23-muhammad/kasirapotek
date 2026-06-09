<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('toast_error', 'Login gagal. Mohon isi semua kolom.');
        }

        // Proses login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Simpan nama dan role di session
            session([
                'name' => $user->name,
                'role' => $user->role,
            ]);

            // 🔄 Redirect berdasarkan role
            if ($user->role === 'Admin') {
                return redirect()->route('dashboard')->with('toast_success', 'Login berhasil sebagai Admin!');
            } elseif ($user->role === 'Cashier') {
                return redirect()->route('cashier')->with('toast_success', 'Login berhasil sebagai Kasir!');
            } else {
                Auth::logout();
                return redirect('/login')->with('toast_error', 'Role tidak dikenali. Hubungi admin.');
            }
        }

        return back()->withInput()->with('toast_error', 'Email atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('toast_success', 'Logout berhasil!');
    }
}
