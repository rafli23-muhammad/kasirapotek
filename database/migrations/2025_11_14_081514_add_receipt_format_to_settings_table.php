<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Tambahkan kolom receipt_format dengan nilai default 'A9' (atau 'default')
            $table->string('receipt_format')->default('A9')->after('default_discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn('receipt_format');
        });
    }
};