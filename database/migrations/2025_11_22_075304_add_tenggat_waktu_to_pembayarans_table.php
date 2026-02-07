<?php
// database/migrations/2024_01_01_000004_add_tenggat_waktu_to_pembayarans_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->date('tenggat_waktu')->nullable()->after('tanggal_bayar');
            $table->text('keterangan_admin')->nullable()->after('keterangan');
        });
    }

    public function down()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn(['tenggat_waktu', 'keterangan_admin']);
        });
    }
};