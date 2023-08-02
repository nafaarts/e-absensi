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
        Schema::table('perizinan', function (Blueprint $table) {
            $table->integer('jumlah_hari')->after('alasan_izin')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perizinan', function (Blueprint $table) {
            $table->dropColumn('jumlah_hari');
        });
    }
};
