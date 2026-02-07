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
        // Perbaiki kolom kategori yang terlalu kecil
        Schema::table('jenis_pembayarans', function (Blueprint $table) {
            $table->string('kategori', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_pembayarans', function (Blueprint $table) {
            $table->string('kategori', 50)->change();
        });
    }
};