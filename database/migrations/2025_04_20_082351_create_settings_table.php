<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name')->nullable();
            $table->string('address')->nullable();
            $table->string('logo')->nullable();
            $table->integer('tax_percentage')->default(0);
            
            // ✅ PERBAIKAN: Nama kolom harus 'default_discount' saja.
            // Nilai default diatur oleh ->default(0).
            $table->integer('default_discount')->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};