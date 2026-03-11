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
        Schema::table('master_parts', function (Blueprint $table) {
            // ANDREW FIX: Menghapus aturan UNIQUE pada kolom part_code
            // Array syntax otomatis mencari nama index: master_parts_part_code_unique
            $table->dropUnique(['part_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_parts', function (Blueprint $table) {
            // Mengembalikan aturan unique jika migration di-rollback
            $table->unique('part_code');
        });
    }
};