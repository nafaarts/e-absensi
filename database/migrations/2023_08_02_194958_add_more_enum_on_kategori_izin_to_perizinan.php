<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE perizinan MODIFY COLUMN kategori_izin ENUM('SAKIT', 'IZIN', 'CUTI', 'DINAS') DEFAULT 'IZIN'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE perizinan MODIFY COLUMN kategori_izin ENUM('SAKIT', 'IZIN') DEFAULT 'IZIN'");
    }
};
