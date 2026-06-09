<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $currentUserId = Auth::id();

        $users = User::where('id', '!=', $currentUserId)->get();

        return view('dashboard.user.index', compact('users'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'Admin') {
            return redirect()->back()->with('toast_error', 'Hanya admin yang dapat mengelola user.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('toast_error', 'Pembuatan User Error: ' . implode(', ', $errors));
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role_id,
        ]);

        return redirect()->back()->with('toast_success', 'User berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'Admin') {
            return redirect()->back()->with('toast_error', 'Hanya admin yang dapat mengelola user.');
        }

        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('toast_error', 'User tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('toast_error', 'Update User Error: ' . implode(', ', $errors));
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('toast_success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'Admin') {
            return redirect()->back()->with('toast_error', 'Hanya admin yang dapat mengelola user.');
        }

        if (Auth::id() == $id) {
            return redirect()->back()->with('toast_error', 'Kamu tidak bisa menghapus akun kamu sendiri.');
        }

        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('toast_error', 'User tidak ditemukan.');
        }

        $user->delete();

        return redirect()->back()->with('toast_success', 'User berhasil dihapus.');
    }
}
