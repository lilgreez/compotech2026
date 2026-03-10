<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_diesets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('product_code')->unique()->nullable();
            $table->integer('production_year')->nullable();
            $table->integer('total_shoot')->default(0);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes(); // Sesuai dengan Model Anda yang memakai SoftDeletes
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_diesets');
    }
};