<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class SettingsController extends Controller
{
    public function index()
    {
        $settings = Settings::first();
        return view('dashboard.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shop_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'default_discount' => 'nullable|numeric|min:0|max:100',
            'receipt_format' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            $errorMessage = implode(', ', $validator->errors()->all());
            return redirect()->back()
                ->with('toast_error', 'Gagal memperbarui pengaturan toko. ' . $errorMessage)
                ->withErrors($validator)
                ->withInput();
        }

        // Ambil data pertama atau buat baru jika belum ada
        $settings = Settings::first() ?? new Settings();

        // Update field dasar
        $settings->shop_name = $request->shop_name ?? $settings->shop_name;
        $settings->address = $request->address ?? $settings->address;

        // Upload logo baru dan hapus yang lama jika ada
        if ($request->hasFile('logo')) {
            if (!empty($settings->logo) && Storage::disk('public')->exists($settings->logo)) {
                Storage::disk('public')->delete($settings->logo);
            }
            $settings->logo = $request->file('logo')->store('logo', 'public');
        }

        // Update nilai pajak, diskon, dan format struk
        $settings->tax_percentage = $request->tax_percentage ?? 0;
        $settings->default_discount = $request->default_discount ?: 0;
        $settings->receipt_format = $request->receipt_format ?? 'default';

        $settings->save();

        return redirect()->back()->with('toast_success', 'Pengaturan toko berhasil diperbarui.');
    }

    public function generateReceiptPdf()
    {
        $settings = Settings::first();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('dashboard.settings.receipt-pdf', compact('settings'));

        $mmToPoint = 2.83465;
        $pdf->setPaper([0, 0, 48 * $mmToPoint, 84 * $mmToPoint], 'portrait');

        return $pdf->stream('receipt-preview.pdf');
    }

    public function receiptView()
    {
        $settings = Settings::first();
        return view('dashboard.settings.receipt-pdf', compact('settings'));
    }
}
