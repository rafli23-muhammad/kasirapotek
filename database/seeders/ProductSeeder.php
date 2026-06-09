<?php

namespace Database\Seeders;

use App\Models\Categories;
use App\Models\Products;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Obat Demam & Nyeri (8)
            ['category' => 'Obat Demam & Nyeri', 'name' => 'Paracetamol 500mg (10 Tablet)', 'purchase_price' => 4500, 'selling_price' => 7500, 'stock' => 160, 'description' => 'Pereda demam dan nyeri ringan.', 'expiry_months' => 24],
            ['category' => 'Obat Demam & Nyeri', 'name' => 'Ibuprofen 200mg (10 Tablet)', 'purchase_price' => 6200, 'selling_price' => 9800, 'stock' => 120, 'description' => 'Membantu meredakan nyeri dan inflamasi.', 'expiry_months' => 20],
            ['category' => 'Obat Demam & Nyeri', 'name' => 'Asam Mefenamat 500mg (10 Tablet)', 'purchase_price' => 7000, 'selling_price' => 11500, 'stock' => 90, 'description' => 'Pereda nyeri sedang.', 'expiry_months' => 18],
            ['category' => 'Obat Demam & Nyeri', 'name' => 'Aspirin 80mg (10 Tablet)', 'purchase_price' => 3800, 'selling_price' => 6500, 'stock' => 75, 'description' => 'Membantu meredakan nyeri.', 'expiry_months' => 24],
            ['category' => 'Obat Demam & Nyeri', 'name' => 'Naproxen 250mg (10 Tablet)', 'purchase_price' => 8500, 'selling_price' => 13000, 'stock' => 55, 'description' => 'Pereda nyeri dan radang.', 'expiry_months' => 20],
            ['category' => 'Obat Demam & Nyeri', 'name' => 'Diclofenac Potassium 50mg (10 Tablet)', 'purchase_price' => 9000, 'selling_price' => 14500, 'stock' => 60, 'description' => 'Pereda nyeri akut.', 'expiry_months' => 22],
            ['category' => 'Obat Demam & Nyeri', 'name' => 'Paracetamol Sirup Dewasa 60ml', 'purchase_price' => 12000, 'selling_price' => 18000, 'stock' => 48, 'description' => 'Sirup pereda demam.', 'expiry_months' => 16],
            ['category' => 'Obat Demam & Nyeri', 'name' => 'Koyo Analgesik (10 Lembar)', 'purchase_price' => 10000, 'selling_price' => 15500, 'stock' => 70, 'description' => 'Membantu mengurangi pegal dan nyeri otot.', 'expiry_months' => 30],

            // Obat Flu & Batuk (8)
            ['category' => 'Obat Flu & Batuk', 'name' => 'OBH Combi Batuk Berdahak 100ml', 'purchase_price' => 11000, 'selling_price' => 17000, 'stock' => 95, 'description' => 'Sirup batuk berdahak.', 'expiry_months' => 14],
            ['category' => 'Obat Flu & Batuk', 'name' => 'OBH Combi Batuk Kering 100ml', 'purchase_price' => 11000, 'selling_price' => 17500, 'stock' => 88, 'description' => 'Sirup batuk kering.', 'expiry_months' => 14],
            ['category' => 'Obat Flu & Batuk', 'name' => 'Konidin Tablet (10 Tablet)', 'purchase_price' => 6500, 'selling_price' => 10500, 'stock' => 110, 'description' => 'Meringankan gejala flu.', 'expiry_months' => 22],
            ['category' => 'Obat Flu & Batuk', 'name' => 'Decolgen Tablet (10 Tablet)', 'purchase_price' => 7000, 'selling_price' => 11500, 'stock' => 100, 'description' => 'Meringankan gejala flu dan hidung tersumbat.', 'expiry_months' => 20],
            ['category' => 'Obat Flu & Batuk', 'name' => 'Mixagrip Flu (10 Tablet)', 'purchase_price' => 5600, 'selling_price' => 9000, 'stock' => 140, 'description' => 'Obat flu dan demam.', 'expiry_months' => 20],
            ['category' => 'Obat Flu & Batuk', 'name' => 'Bodrex Flu & Batuk (10 Kaplet)', 'purchase_price' => 6000, 'selling_price' => 9800, 'stock' => 120, 'description' => 'Meredakan flu dan batuk.', 'expiry_months' => 22],
            ['category' => 'Obat Flu & Batuk', 'name' => 'Vicks Formula 44 Sirup 54ml', 'purchase_price' => 13500, 'selling_price' => 21000, 'stock' => 65, 'description' => 'Sirup batuk untuk dewasa.', 'expiry_months' => 16],
            ['category' => 'Obat Flu & Batuk', 'name' => 'Hufagrip Forte Sirup 60ml', 'purchase_price' => 12000, 'selling_price' => 18500, 'stock' => 70, 'description' => 'Sirup flu untuk anak.', 'expiry_months' => 15],

            // Vitamin & Suplemen (8)
            ['category' => 'Vitamin & Suplemen', 'name' => 'Vitamin C 500mg (30 Tablet)', 'purchase_price' => 18500, 'selling_price' => 28000, 'stock' => 85, 'description' => 'Membantu menjaga daya tahan tubuh.', 'expiry_months' => 24],
            ['category' => 'Vitamin & Suplemen', 'name' => 'Imboost Tablet (10 Tablet)', 'purchase_price' => 26000, 'selling_price' => 36000, 'stock' => 52, 'description' => 'Suplemen imun harian.', 'expiry_months' => 18],
            ['category' => 'Vitamin & Suplemen', 'name' => 'Redoxon Double Action (10 Tablet)', 'purchase_price' => 43000, 'selling_price' => 59000, 'stock' => 44, 'description' => 'Vitamin C dan zinc.', 'expiry_months' => 20],
            ['category' => 'Vitamin & Suplemen', 'name' => 'Sangobion Kapsul (10 Kapsul)', 'purchase_price' => 14000, 'selling_price' => 22000, 'stock' => 68, 'description' => 'Suplemen penambah darah.', 'expiry_months' => 24],
            ['category' => 'Vitamin & Suplemen', 'name' => 'Enervon-C (10 Tablet)', 'purchase_price' => 8500, 'selling_price' => 13500, 'stock' => 96, 'description' => 'Vitamin C + multivitamin.', 'expiry_months' => 22],
            ['category' => 'Vitamin & Suplemen', 'name' => 'Blackmores Bio C 1000mg (30 Tablet)', 'purchase_price' => 115000, 'selling_price' => 149000, 'stock' => 18, 'description' => 'Vitamin C dosis tinggi.', 'expiry_months' => 26],
            ['category' => 'Vitamin & Suplemen', 'name' => 'Curcuma Plus Emulsion 200ml', 'purchase_price' => 24000, 'selling_price' => 34000, 'stock' => 36, 'description' => 'Suplemen nafsu makan anak.', 'expiry_months' => 14],
            ['category' => 'Vitamin & Suplemen', 'name' => 'Fish Oil 1000mg (30 Softgel)', 'purchase_price' => 52000, 'selling_price' => 76000, 'stock' => 30, 'description' => 'Suplemen omega-3.', 'expiry_months' => 28],

            // Obat Lambung & Pencernaan (8)
            ['category' => 'Obat Lambung & Pencernaan', 'name' => 'Promag Tablet (12 Tablet)', 'purchase_price' => 8000, 'selling_price' => 12500, 'stock' => 130, 'description' => 'Meredakan maag dan asam lambung.', 'expiry_months' => 20],
            ['category' => 'Obat Lambung & Pencernaan', 'name' => 'Mylanta Cair 50ml', 'purchase_price' => 9800, 'selling_price' => 15500, 'stock' => 74, 'description' => 'Antasida cair untuk lambung.', 'expiry_months' => 16],
            ['category' => 'Obat Lambung & Pencernaan', 'name' => 'Polysilane Tablet (8 Tablet)', 'purchase_price' => 6200, 'selling_price' => 9800, 'stock' => 95, 'description' => 'Meredakan gejala dispepsia.', 'expiry_months' => 19],
            ['category' => 'Obat Lambung & Pencernaan', 'name' => 'Oralit Sachet', 'purchase_price' => 2200, 'selling_price' => 4500, 'stock' => 180, 'description' => 'Mencegah dehidrasi karena diare.', 'expiry_months' => 24],
            ['category' => 'Obat Lambung & Pencernaan', 'name' => 'Entrostop Tablet (12 Tablet)', 'purchase_price' => 9000, 'selling_price' => 14500, 'stock' => 102, 'description' => 'Membantu mengatasi diare.', 'expiry_months' => 21],
            ['category' => 'Obat Lambung & Pencernaan', 'name' => 'New Diatabs (10 Tablet)', 'purchase_price' => 7800, 'selling_price' => 12500, 'stock' => 98, 'description' => 'Obat diare untuk dewasa.', 'expiry_months' => 22],
            ['category' => 'Obat Lambung & Pencernaan', 'name' => 'Laxing Sirup 60ml', 'purchase_price' => 16000, 'selling_price' => 24500, 'stock' => 40, 'description' => 'Membantu melancarkan BAB.', 'expiry_months' => 13],
            ['category' => 'Obat Lambung & Pencernaan', 'name' => 'L-Bio Probiotik Sachet', 'purchase_price' => 14000, 'selling_price' => 22000, 'stock' => 62, 'description' => 'Suplemen probiotik pencernaan.', 'expiry_months' => 17],

            // Perawatan Luka & Antiseptik (8)
            ['category' => 'Perawatan Luka & Antiseptik', 'name' => 'Betadine Solution 15ml', 'purchase_price' => 11000, 'selling_price' => 17000, 'stock' => 84, 'description' => 'Antiseptik pembersih luka.', 'expiry_months' => 24],
            ['category' => 'Perawatan Luka & Antiseptik', 'name' => 'Betadine Salep 5gr', 'purchase_price' => 14500, 'selling_price' => 22000, 'stock' => 56, 'description' => 'Salep antiseptik untuk luka ringan.', 'expiry_months' => 20],
            ['category' => 'Perawatan Luka & Antiseptik', 'name' => 'Hansaplast Reguler (20 Pcs)', 'purchase_price' => 9000, 'selling_price' => 14500, 'stock' => 96, 'description' => 'Plester luka kecil.', 'expiry_months' => 36],
            ['category' => 'Perawatan Luka & Antiseptik', 'name' => 'Kasa Steril 16x16 (10 pcs)', 'purchase_price' => 8500, 'selling_price' => 13000, 'stock' => 90, 'description' => 'Kasa steril untuk perawatan luka.', 'expiry_months' => 30],
            ['category' => 'Perawatan Luka & Antiseptik', 'name' => 'Alkohol Swab (100 pcs)', 'purchase_price' => 14000, 'selling_price' => 22000, 'stock' => 58, 'description' => 'Pembersih antiseptik sekali pakai.', 'expiry_months' => 28],
            ['category' => 'Perawatan Luka & Antiseptik', 'name' => 'Rivanol 100ml', 'purchase_price' => 7000, 'selling_price' => 11500, 'stock' => 64, 'description' => 'Antiseptik cair untuk luka.', 'expiry_months' => 22],
            ['category' => 'Perawatan Luka & Antiseptik', 'name' => 'Povidone Iodine 60ml', 'purchase_price' => 12500, 'selling_price' => 19500, 'stock' => 52, 'description' => 'Antiseptik untuk kulit dan luka.', 'expiry_months' => 22],
            ['category' => 'Perawatan Luka & Antiseptik', 'name' => 'Hydrogen Peroxide 3% 100ml', 'purchase_price' => 9000, 'selling_price' => 14500, 'stock' => 38, 'description' => 'Larutan pembersih luka.', 'expiry_months' => 18],

            // Alat Kesehatan (8, sebagian besar non-expired)
            ['category' => 'Alat Kesehatan', 'name' => 'Masker Medis 3 Ply (50 pcs)', 'purchase_price' => 20000, 'selling_price' => 32000, 'stock' => 72, 'description' => 'Masker sekali pakai.', 'expiry_months' => 30],
            ['category' => 'Alat Kesehatan', 'name' => 'Termometer Digital', 'purchase_price' => 33000, 'selling_price' => 52000, 'stock' => 30, 'description' => 'Alat ukur suhu tubuh.', 'expiry_months' => null],
            ['category' => 'Alat Kesehatan', 'name' => 'Tensimeter Digital', 'purchase_price' => 185000, 'selling_price' => 245000, 'stock' => 12, 'description' => 'Alat ukur tekanan darah otomatis.', 'expiry_months' => null],
            ['category' => 'Alat Kesehatan', 'name' => 'Nebulizer Portable', 'purchase_price' => 280000, 'selling_price' => 365000, 'stock' => 8, 'description' => 'Alat terapi uap saluran pernapasan.', 'expiry_months' => null],
            ['category' => 'Alat Kesehatan', 'name' => 'Strip Gula Darah (25 strip)', 'purchase_price' => 75000, 'selling_price' => 98000, 'stock' => 24, 'description' => 'Strip untuk glukometer.', 'expiry_months' => 18],
            ['category' => 'Alat Kesehatan', 'name' => 'Jarum Lancet (100 pcs)', 'purchase_price' => 26000, 'selling_price' => 39000, 'stock' => 28, 'description' => 'Jarum lancet untuk tes gula darah.', 'expiry_months' => 36],
            ['category' => 'Alat Kesehatan', 'name' => 'Pulse Oximeter', 'purchase_price' => 95000, 'selling_price' => 139000, 'stock' => 16, 'description' => 'Alat ukur saturasi oksigen.', 'expiry_months' => null],
            ['category' => 'Alat Kesehatan', 'name' => 'Sarung Tangan Medis (100 pcs)', 'purchase_price' => 42000, 'selling_price' => 62000, 'stock' => 26, 'description' => 'Sarung tangan medis sekali pakai.', 'expiry_months' => 24],

            // Ibu & Anak (8)
            ['category' => 'Ibu & Anak', 'name' => 'Minyak Telon 60ml', 'purchase_price' => 10000, 'selling_price' => 16000, 'stock' => 82, 'description' => 'Memberi rasa hangat pada bayi.', 'expiry_months' => 24],
            ['category' => 'Ibu & Anak', 'name' => 'Paracetamol Sirup Anak 60ml', 'purchase_price' => 16500, 'selling_price' => 25000, 'stock' => 58, 'description' => 'Pereda demam anak.', 'expiry_months' => 15],
            ['category' => 'Ibu & Anak', 'name' => 'Vitamin D3 Drop Bayi 10ml', 'purchase_price' => 38000, 'selling_price' => 54000, 'stock' => 26, 'description' => 'Suplemen vitamin D untuk bayi.', 'expiry_months' => 18],
            ['category' => 'Ibu & Anak', 'name' => 'Krim Ruam Popok 20gr', 'purchase_price' => 24000, 'selling_price' => 34000, 'stock' => 34, 'description' => 'Membantu mencegah iritasi popok.', 'expiry_months' => 20],
            ['category' => 'Ibu & Anak', 'name' => 'Oral Syringe 5ml', 'purchase_price' => 4500, 'selling_price' => 8000, 'stock' => 48, 'description' => 'Alat bantu takar obat bayi.', 'expiry_months' => null],
            ['category' => 'Ibu & Anak', 'name' => 'Nasal Aspirator Bayi', 'purchase_price' => 16000, 'selling_price' => 25000, 'stock' => 22, 'description' => 'Alat sedot lendir bayi.', 'expiry_months' => null],
            ['category' => 'Ibu & Anak', 'name' => 'Cotton Bud Bayi (100 pcs)', 'purchase_price' => 8000, 'selling_price' => 12500, 'stock' => 70, 'description' => 'Cotton bud lembut untuk bayi.', 'expiry_months' => 36],
            ['category' => 'Ibu & Anak', 'name' => 'Saline Nasal Spray Anak 20ml', 'purchase_price' => 27000, 'selling_price' => 39000, 'stock' => 24, 'description' => 'Membersihkan hidung tersumbat anak.', 'expiry_months' => 16],
        ];

        foreach ($products as $item) {
            $category = Categories::where('name', $item['category'])->first();
            if (!$category) {
                continue;
            }

            $slugName = strtolower(str_replace([' ', '/', '(', ')'], ['-', '-', '', ''], $item['name']));
            $slugName = preg_replace('/[^a-z0-9\-]/', '', $slugName);
            $imagePath = "products/apotek-{$slugName}.svg";

            if (!Storage::disk('public')->exists($imagePath)) {
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="320" height="240" viewBox="0 0 320 240">'
                    . '<rect width="320" height="240" fill="#E8F5E9"/>'
                    . '<rect x="20" y="20" width="280" height="200" rx="16" fill="#FFFFFF" stroke="#43A047" stroke-width="2"/>'
                    . '<text x="160" y="105" text-anchor="middle" font-size="18" font-family="Arial, sans-serif" fill="#2E7D32">APOTEK</text>'
                    . '<text x="160" y="135" text-anchor="middle" font-size="14" font-family="Arial, sans-serif" fill="#1B5E20">'
                    . htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8')
                    . '</text>'
                    . '</svg>';

                Storage::disk('public')->put($imagePath, $svg);
            }

            Products::updateOrCreate(
                ['name' => $item['name']],
                [
                    'category_id' => $category->id,
                    'description' => $item['description'],
                    'purchase_price' => $item['purchase_price'],
                    'selling_price' => $item['selling_price'],
                    'stock' => $item['stock'],
                    'low_stock_threshold' => 5,
                    'expiry_date' => is_null($item['expiry_months'])
                        ? null
                        : Carbon::now()->addMonths($item['expiry_months'])->toDateString(),
                    'image' => $imagePath,
                ]
            );
        }
    }
}
