<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->updateOrInsert(
            ['id' => 1],
            [
                'shop_name' => 'Jaya Utama',
                'address' => 'Jl. Raya No. 123, Jakarta',
                'logo' => 'logo/j6VJHBZYSWQgLduGO9ay1QLsRWWl4Tqqmz68SZBf.png',
                'tax_percentage' => 10,
                'default_discount' => 2,
                'receipt_format' => 'A9',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
