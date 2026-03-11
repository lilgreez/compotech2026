<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_inspections', function (Blueprint $table) {
            // Menambahkan kolom 'type' untuk membedakan tab (kerusakan, tindakan, alasan)
            $table->string('type')->default('kerusakan')->after('id');
            
            // Membuat part_id menjadi nullable karena ini adalah master data global
            $table->unsignedBigInteger('part_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('master_inspections', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->unsignedBigInteger('part_id')->nullable(false)->change();
        });
    }
};