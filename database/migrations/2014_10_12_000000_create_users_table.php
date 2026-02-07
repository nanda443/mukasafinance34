<?php
// database/migrations/2024_01_01_000001_create_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('nis')->unique()->nullable();
            $table->enum('kelas', ['10', '11', '12'])->nullable();
            $table->enum('jurusan', ['IPA', 'IPS'])->nullable();
            $table->enum('role', ['admin', 'bendahara', 'siswa'])->default('siswa');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};