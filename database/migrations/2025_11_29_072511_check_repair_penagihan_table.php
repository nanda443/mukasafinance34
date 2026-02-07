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
        // Cek apakah table sudah ada, jika belum buat
        if (!Schema::hasTable('penagihans')) {
            Schema::create('penagihans', function (Blueprint $table) {
                $table->id();
                $table->string('judul', 255);
                $table->text('deskripsi')->nullable();
                $table->decimal('nominal', 15, 2);
                $table->enum('jenis', ['bulanan', 'tahunan', 'bebas'])->default('bebas');
                $table->enum('target', ['massal', 'individu'])->default('massal');
                $table->string('kelas', 10)->nullable();
                $table->string('jurusan', 50)->nullable();
                $table->longText('target_siswa')->nullable();
                $table->date('tenggat_waktu');
                $table->boolean('status')->default(true);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
        } else {
            // Jika table sudah ada, pastikan semua column ada dengan modify jika perlu
            Schema::table('penagihans', function (Blueprint $table) {
                // Cek dan tambahkan column jika belum ada
                if (!Schema::hasColumn('penagihans', 'judul')) {
                    $table->string('judul', 255)->after('id');
                }
                if (!Schema::hasColumn('penagihans', 'deskripsi')) {
                    $table->text('deskripsi')->nullable()->after('judul');
                }
                if (!Schema::hasColumn('penagihans', 'nominal')) {
                    $table->decimal('nominal', 15, 2)->after('deskripsi');
                }
                if (!Schema::hasColumn('penagihans', 'jenis')) {
                    $table->enum('jenis', ['bulanan', 'tahunan', 'bebas'])->default('bebas')->after('nominal');
                }
                if (!Schema::hasColumn('penagihans', 'target')) {
                    $table->enum('target', ['massal', 'individu'])->default('massal')->after('jenis');
                }
                if (!Schema::hasColumn('penagihans', 'kelas')) {
                    $table->string('kelas', 10)->nullable()->after('target');
                }
                if (!Schema::hasColumn('penagihans', 'jurusan')) {
                    $table->string('jurusan', 50)->nullable()->after('kelas');
                }
                if (!Schema::hasColumn('penagihans', 'target_siswa')) {
                    $table->longText('target_siswa')->nullable()->after('jurusan');
                }
                if (!Schema::hasColumn('penagihans', 'tenggat_waktu')) {
                    $table->date('tenggat_waktu')->after('target_siswa');
                }
                if (!Schema::hasColumn('penagihans', 'status')) {
                    $table->boolean('status')->default(true)->after('tenggat_waktu');
                }
                if (!Schema::hasColumn('penagihans', 'created_by')) {
                    $table->unsignedBigInteger('created_by')->nullable()->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penagihans');
    }
};