<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            // FIX ANDREW 1: user_id harus nullable agar bisa mencatat aktivitas sistem (cronjob/CLI)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // FIX ANDREW 2: Memperlebar action string untuk menampung "_SYSTEM"
            $table->string('action'); 
            
            $table->string('table_name');
            $table->unsignedBigInteger('record_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();

            // FIX ANDREW 3: Menambahkan Composite Indexing! Ini akan mencegah CPU Server jebol saat filter tabel diakses.
            $table->index(['table_name', 'created_at']);
            $table->index(['user_id', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};