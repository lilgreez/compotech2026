<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Modul 9: Permintaan Mold Baru (SPK & Progress Tracking).
     */
    public function up(): void
    {
        Schema::create('mold_requests', function (Blueprint $table) {
            $table->id();
            $table->string('spk_number')->unique();
            $table->string('mold_name');
            $table->string('customer');
            $table->string('pic_name')->nullable();
            $table->date('start_date');
            $table->date('target_date');
            $table->enum('status', ['Draft', 'In Progress', 'Completed', 'Cancelled'])->default('In Progress');
            $table->unsignedInteger('total_progress')->default(0); // 0-100%
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('mold_request_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mold_request_id')->constrained('mold_requests')->onDelete('cascade');
            $table->string('stage_name'); // Desain, Machining, Assembly, Trial, Finishing
            $table->unsignedInteger('stage_order')->default(1);
            $table->unsignedInteger('weight_percent')->default(20); // Bobot %
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('mold_request_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mold_request_id')->constrained('mold_requests')->onDelete('cascade');
            $table->string('part_name');
            $table->unsignedInteger('required_qty')->default(1);
            $table->unsignedInteger('available_qty')->default(0);
            $table->enum('status', ['Tersedia', 'Menunggu Pengadaan', 'Selesai'])->default('Menunggu Pengadaan');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mold_request_materials');
        Schema::dropIfExists('mold_request_stages');
        Schema::dropIfExists('mold_requests');
    }
};
