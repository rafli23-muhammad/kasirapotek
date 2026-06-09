<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Categories::paginate(10);
        return view('dashboard.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('category')
                ->withErrors($validator)
                ->withInput()
                ->with('toast_error', 'Gagal menambahkan kategori.');
        }

        Categories::create([
            'name' => $request->name,
        ]);

        return redirect()->route('category')->with('toast_success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('category')
                ->withErrors($validator)
                ->withInput()
                ->with('toast_error', 'Gagal memperbarui kategori.');
        }

        $category = Categories::findOrFail($id);
        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('category')->with('toast_success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = Categories::findOrFail($id);
        $category->delete();

        return redirect()->route('category')->with('toast_success', 'Kategori berhasil dihapus.');
    }
}
