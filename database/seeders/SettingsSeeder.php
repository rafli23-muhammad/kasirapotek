<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'shop_name' => "Jaya Utama",
            'address' => "Jl. Raya No. 123, Jakarta",
            'logo' => 'logo/j6VJHBZYSWQgLduGO9ay1QLsRWWl4Tqqmz68SZBf.png',
            'tax_percentage' => 10,
            'default_discount ?? 0 ' => 2,
        ]);
    }
}
