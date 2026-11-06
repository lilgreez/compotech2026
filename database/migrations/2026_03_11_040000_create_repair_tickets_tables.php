<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Modul 10: Tiket Perbaikan Mold (Repair Ticketing).
     */
    public function up(): void
    {
        Schema::create('repair_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('dieset_id')->constrained('master_diesets')->onDelete('cascade');
            $table->foreignId('part_id')->nullable()->constrained('master_parts')->nullOnDelete();
            $table->foreignId('reporter_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('technician_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('issue_description');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->string('evidence_photo')->nullable();
            $table->enum('status', ['Open', 'In Progress', 'Pending Part', 'Resolved', 'Closed'])->default('Open');
            $table->unsignedInteger('progress_percent')->default(0); // 0-100%
            $table->text('technician_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('repair_ticket_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_ticket_id')->constrained('repair_tickets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // Tiket Dibuat, Teknisi Ditugaskan, Update Progress, Selesai
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_ticket_logs');
        Schema::dropIfExists('repair_tickets');
    }
};
