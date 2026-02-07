<?php
// database/migrations/2024_01_01_create_penagihans_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penagihans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->decimal('nominal', 12, 2);
            $table->enum('jenis', ['bulanan', 'tahunan', 'bebas']);
            $table->enum('target', ['massal', 'individu']);
            $table->date('tenggat_waktu');
            $table->json('target_siswa')->nullable();
            $table->string('kelas')->nullable();
            $table->string('jurusan')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Tambahkan kolom penagihan_id ke jenis_pembayarans
        Schema::table('jenis_pembayarans', function (Blueprint $table) {
            $table->foreignId('penagihan_id')->nullable()->after('id')->constrained('penagihans')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('jenis_pembayarans', function (Blueprint $table) {
            $table->dropForeign(['penagihan_id']);
            $table->dropColumn('penagihan_id');
        });
        
        Schema::dropIfExists('penagihans');
    }
};