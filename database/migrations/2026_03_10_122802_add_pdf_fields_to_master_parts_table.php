<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_parts', function (Blueprint $table) {
            $table->string('category')->nullable()->after('dieset_id'); // Kategori seperti ANGULAR PIN, CORE BLOCK
            $table->integer('actual_shoot')->default(0)->after('description');
            $table->integer('max_shoot')->default(3000000)->after('actual_shoot');
        });
    }

    public function down(): void
    {
        Schema::table('master_parts', function (Blueprint $table) {
            $table->dropColumn(['category', 'actual_shoot', 'max_shoot']);
        });
    }
};