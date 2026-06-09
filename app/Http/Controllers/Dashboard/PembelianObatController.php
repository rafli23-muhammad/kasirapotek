<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LogStock;

class PembelianObatController extends Controller
{
    public function index()
    {
        $logs = LogStock::with(['product', 'user'])
            ->where('type', 'in')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('dashboard.pembelian.index', compact('logs'));
    }
}
