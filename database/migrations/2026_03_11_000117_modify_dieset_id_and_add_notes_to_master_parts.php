<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_parts', function (Blueprint $table) {
            // Mengizinkan dieset_id menjadi NULL (Sebagai penanda Data Katalog)
            $table->unsignedBigInteger('dieset_id')->nullable()->change();
            // Menambahkan kolom Item Notes sesuai form Halaman 26 PDF
            $table->text('item_notes')->nullable()->after('max_shoot');
        });
    }

    public function down(): void
    {
        Schema::table('master_parts', function (Blueprint $table) {
            $table->unsignedBigInteger('dieset_id')->nullable(false)->change();
            $table->dropColumn('item_notes');
        });
    }
};