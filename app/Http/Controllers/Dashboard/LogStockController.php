<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LogStock;
use App\Models\Products;
use Illuminate\Http\Request;

class LogStockController extends Controller
{
    public function index($id)
    {
        $product = Products::where('id', $id)->first();
        $logStocks = LogStock::where('product_id', $id)->paginate(10);
        return view('dashboard.log_stock.index', compact('logStocks', 'product'));
    }
}
