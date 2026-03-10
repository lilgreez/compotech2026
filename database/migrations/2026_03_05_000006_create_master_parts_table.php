<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dieset_id')->constrained('master_diesets')->onDelete('cascade');
            $table->string('name');
            $table->string('part_code')->unique()->nullable();
            $table->string('cavity_number')->nullable();
            $table->text('description')->nullable();
            $table->integer('current_stock')->default(0);
            $table->string('image_path')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_parts');
    }
};