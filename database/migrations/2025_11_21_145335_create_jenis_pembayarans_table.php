<?php
// database/migrations/2024_01_01_000002_create_jenis_pembayarans_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jenis_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('nominal', 15, 2);
            $table->enum('kategori', ['SPP', 'Gedung', 'Praktikum', 'Lainnya']);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_pembayarans');
    }
};