<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Try dropping unique indices safely if they exist
        try {
            DB::statement("ALTER TABLE master_diesets DROP INDEX master_diesets_name_unique");
        } catch (\Throwable $e) {}

        try {
            DB::statement("ALTER TABLE master_diesets DROP INDEX master_diesets_product_code_unique");
        } catch (\Throwable $e) {}

        Schema::table('master_diesets', function (Blueprint $table) {
            if (!Schema::hasColumn('master_diesets', 'kode_mold')) {
                $table->string('kode_mold', 100)->nullable()->index()->after('id');
            }
            if (!Schema::hasColumn('master_diesets', 'cavity')) {
                $table->string('cavity', 50)->nullable()->after('name');
            }
            if (!Schema::hasColumn('master_diesets', 'ownership')) {
                $table->string('ownership', 255)->nullable()->after('status');
            }
            if (!Schema::hasColumn('master_diesets', 'type_cavity')) {
                $table->string('type_cavity', 50)->nullable()->after('ownership');
            }
            if (!Schema::hasColumn('master_diesets', 'dimensi_mold')) {
                $table->string('dimensi_mold', 100)->nullable()->after('production_year');
            }
            if (!Schema::hasColumn('master_diesets', 'diameter_lokating_ring')) {
                $table->string('diameter_lokating_ring', 50)->nullable()->after('dimensi_mold');
            }
            if (!Schema::hasColumn('master_diesets', 'type_cooling')) {
                $table->string('type_cooling', 50)->nullable()->after('diameter_lokating_ring');
            }
            if (!Schema::hasColumn('master_diesets', 'type_eject')) {
                $table->string('type_eject', 50)->nullable()->after('type_cooling');
            }
            if (!Schema::hasColumn('master_diesets', 'type_gate')) {
                $table->string('type_gate', 50)->nullable()->after('type_eject');
            }
            if (!Schema::hasColumn('master_diesets', 'type_mold')) {
                $table->string('type_mold', 50)->nullable()->after('type_gate');
            }
            if (!Schema::hasColumn('master_diesets', 'ukuran_naple')) {
                $table->string('ukuran_naple', 50)->nullable()->after('type_mold');
            }
            if (!Schema::hasColumn('master_diesets', 'qty_naple')) {
                $table->string('qty_naple', 50)->nullable()->after('ukuran_naple');
            }
            if (!Schema::hasColumn('master_diesets', 'naple_std_long')) {
                $table->string('naple_std_long', 50)->nullable()->after('qty_naple');
            }
            if (!Schema::hasColumn('master_diesets', 'jumlah_cavity_std')) {
                $table->string('jumlah_cavity_std', 50)->nullable()->after('naple_std_long');
            }
            if (!Schema::hasColumn('master_diesets', 'jumlah_piston_slider')) {
                $table->string('jumlah_piston_slider', 50)->nullable()->after('jumlah_cavity_std');
            }
            if (!Schema::hasColumn('master_diesets', 'jumlah_gasvent')) {
                $table->string('jumlah_gasvent', 50)->nullable()->after('jumlah_piston_slider');
            }
            if (!Schema::hasColumn('master_diesets', 'safety_mold')) {
                $table->string('safety_mold', 100)->nullable()->after('jumlah_gasvent');
            }
            if (!Schema::hasColumn('master_diesets', 'qty_temp_channel_hr')) {
                $table->string('qty_temp_channel_hr', 100)->nullable()->after('safety_mold');
            }
        });

        try {
            DB::statement("ALTER TABLE master_diesets MODIFY COLUMN production_year VARCHAR(50) NULL");
            DB::statement("ALTER TABLE master_diesets MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'Active'");
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_diesets', function (Blueprint $table) {
            $table->dropColumn([
                'kode_mold',
                'cavity',
                'ownership',
                'type_cavity',
                'dimensi_mold',
                'diameter_lokating_ring',
                'type_cooling',
                'type_eject',
                'type_gate',
                'type_mold',
                'ukuran_naple',
                'qty_naple',
                'naple_std_long',
                'jumlah_cavity_std',
                'jumlah_piston_slider',
                'jumlah_gasvent',
                'safety_mold',
                'qty_temp_channel_hr',
            ]);
        });
    }
};
