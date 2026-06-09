<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Products;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $expiryStatus = request('expiry_status', 'all');
        $today = Carbon::today();
        $nearExpiredLimit = Carbon::today()->addDays(30);

        $productsQuery = Products::with('category');

        if ($expiryStatus === 'expired') {
            $productsQuery->whereNotNull('expiry_date')
                ->whereDate('expiry_date', '<', $today);
        } elseif ($expiryStatus === 'near_expired') {
            $productsQuery->whereNotNull('expiry_date')
                ->whereDate('expiry_date', '>=', $today)
                ->whereDate('expiry_date', '<=', $nearExpiredLimit);
        }

        $products = $productsQuery
            ->orderByRaw('CASE WHEN expiry_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('expiry_date')
            ->paginate(10)
            ->withQueryString();
        $categories = Categories::all();

        $expiredCount = Products::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', $today)
            ->count();

        $nearExpiredCount = Products::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '>=', $today)
            ->whereDate('expiry_date', '<=', $nearExpiredLimit)
            ->count();

        return view('dashboard.product.index', compact(
            'products',
            'categories',
            'expiryStatus',
            'expiredCount',
            'nearExpiredCount'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'description.required' => 'Deskripsi produk wajib diisi.',
            'purchase_price.required' => 'Harga beli produk wajib diisi.',
            'selling_price.required' => 'Harga jual produk wajib diisi.',
            'stock.required' => 'Stok produk wajib diisi.',
            'low_stock_threshold.required' => 'Batas stok sedikit wajib diisi.',
            'expiry_date.date' => 'Tanggal kedaluwarsa tidak valid.',
            'image.image' => 'File gambar harus berformat gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('product')
                ->withErrors($validator)
                ->withInput()
                ->with('toast_error', 'Gagal menambahkan produk.');
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Products::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock,
            'low_stock_threshold' => $request->low_stock_threshold,
            'expiry_date' => $request->expiry_date,
            'image' => $imagePath,
        ]);

        return redirect()->route('product')->with('toast_success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'description.required' => 'Deskripsi produk wajib diisi.',
            'purchase_price.required' => 'Harga beli produk wajib diisi.',
            'selling_price.required' => 'Harga jual produk wajib diisi.',
            'stock.required' => 'Stok produk wajib diisi.',
            'low_stock_threshold.required' => 'Batas stok sedikit wajib diisi.',
            'expiry_date.date' => 'Tanggal kedaluwarsa tidak valid.',
            'image.image' => 'File gambar harus berformat gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('product')
                ->withErrors($validator)
                ->withInput()
                ->with('toast_error', 'Gagal memperbarui produk.');
        }

        $product = Products::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::exists('public/' . $product->image)) {
                Storage::delete('public/' . $product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock,
            'low_stock_threshold' => $request->low_stock_threshold,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->route('product')->with('toast_success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = Products::findOrFail($id);

        if ($product->image && Storage::exists('public/' . $product->image)) {
            Storage::delete('public/' . $product->image);
        }

        $product->delete();

        return redirect()->route('product')->with('toast_success', 'Produk berhasil dihapus.');
    }

    public function printExpired()
    {
        $today = Carbon::today();
        $nearExpiredLimit = Carbon::today()->addDays(30);

        $products = Products::with('category')
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', $nearExpiredLimit)
            ->orderBy('expiry_date')
            ->get()
            ->map(function ($product) use ($today) {
                $status = 'Aman';
                if ($product->expiry_date && Carbon::parse($product->expiry_date)->lt($today)) {
                    $status = 'Expired';
                } elseif ($product->expiry_date && Carbon::parse($product->expiry_date)->betweenIncluded($today, Carbon::today()->addDays(30))) {
                    $status = 'Hampir Expired';
                }

                $product->expiry_status = $status;
                return $product;
            });

        return view('dashboard.product.print-expired', compact('products'));
    }
}
