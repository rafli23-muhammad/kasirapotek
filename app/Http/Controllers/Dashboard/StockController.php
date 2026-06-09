<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\LogStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $sortOrder = $request->input('sort', 'asc');

        $products = Products::orderBy('stock', $sortOrder)->paginate(10);

        foreach ($products as $product) {
            $stock = $product->stock;
            $threshold = $product->low_stock_threshold;

            if ($stock < $threshold) {
                $product->status = 'Peringatan Stok';
            } elseif ($stock <= ($threshold + ($threshold * 0.2))) {
                $product->status = 'Stok Hampir Habis';
            } else {
                $product->status = 'Stok Aman';
            }
        }

        return view('dashboard.stock.index', compact('products'));
    }

    public function updateStock(Request $request, $id)
    {
        $product = Products::findOrFail($id);
        $user = Auth::user();

        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $oldStock = $product->stock;
        $newStock = $request->stock;
        $diffStock = $newStock - $oldStock;

        $product->update([
            'stock' => $newStock,
        ]);

        if ($diffStock !== 0) {
            LogStock::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'type' => $diffStock > 0 ? 'in' : 'out',
                'stock' => abs($diffStock),
                'description' => "{$user->name} telah " .
                    ($diffStock > 0 ? "menambahkan" : "mengurangi") .
                    " " . abs($diffStock) . " Stock {$product->name} pada tanggal " .
                    now()->locale('id')->translatedFormat('j F Y, H:i'),
            ]);
        }

        return redirect()->back()->with('toast_success', 'Stok produk berhasil diperbarui!');
    }
}
