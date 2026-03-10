<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspection_histories', function (Blueprint $table) {
            // Menyimpan angka shoot pada saat inspeksi dilakukan (sesuai PDF hal 13)
            $table->integer('parts_shoot')->default(0)->after('inspection_date');
            $table->integer('total_shoot')->default(0)->after('parts_shoot');
        });
    }

    public function down(): void
    {
        Schema::table('inspection_histories', function (Blueprint $table) {
            $table->dropColumn(['parts_shoot', 'total_shoot']);
        });
    }
};