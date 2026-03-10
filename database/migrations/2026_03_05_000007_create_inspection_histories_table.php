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
        Schema::create('inspection_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained('master_parts')->onDelete('cascade');
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('inspection_date');
            $table->enum('condition', ['OK', 'Repair', 'Replace']);
            $table->text('damage_details')->nullable();
            $table->text('action_taken')->nullable();
            $table->text('reason')->nullable();
            $table->string('evidence_photo_path')->nullable();
            $table->boolean('report_sent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_histories');
    }
};
