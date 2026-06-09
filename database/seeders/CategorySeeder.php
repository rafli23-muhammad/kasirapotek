<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Obat Demam & Nyeri',
            'Obat Flu & Batuk',
            'Vitamin & Suplemen',
            'Obat Lambung & Pencernaan',
            'Perawatan Luka & Antiseptik',
            'Alat Kesehatan',
            'Ibu & Anak',
        ];

        foreach ($categories as $categoryName) {
            Categories::firstOrCreate([
                'name' => $categoryName,
            ]);
        }
    }
}
