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
            $table->json('kustom_tanggal')->nullable()->after('jumlah_hari');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perizinan', function (Blueprint $table) {
            $table->dropColumn('kustom_tanggal');
        });
    }
};
