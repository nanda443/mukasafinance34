<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use App\Models\User;
use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@smamuhkasihan.sch.id',
            'nis' => 'admin001',
            'role' => 'admin',
            'password' => Hash::make('password123')
        ]);

        // Create Bendahara
        User::create([
            'name' => 'Bendahara Sekolah',
            'email' => 'bendahara@smamuhkasihan.sch.id',
            'nis' => 'bendahara001',
            'role' => 'bendahara',
            'password' => Hash::make('password123')
        ]);

        // Create Sample Students
        $kelas = ['10', '11', '12'];
        $jurusan = ['IPA', 'IPS'];
        
        for ($i = 1; $i <= 30; $i++) {
            User::create([
                'name' => 'Siswa ' . $i,
                'email' => 'siswa' . $i . '@smamuhkasihan.sch.id',
                'nis' => 'S' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'kelas' => $kelas[array_rand($kelas)],
                'jurusan' => $jurusan[array_rand($jurusan)],
                'role' => 'siswa',
                'password' => Hash::make('password123')
            ]);
        }

        // Create Jenis Pembayaran
        $jenisPembayaran = [
            [
                'nama' => 'SPP Bulanan',
                'nominal' => 150000,
                'kategori' => 'SPP',
                'status' => true
            ],
            [
                'nama' => 'Uang Gedung',
                'nominal' => 5000000,
                'kategori' => 'Gedung',
                'status' => true
            ],
            [
                'nama' => 'Praktikum IPA',
                'nominal' => 200000,
                'kategori' => 'Praktikum',
                'status' => true
            ],
            [
                'nama' => 'Kegiatan Sekolah',
                'nominal' => 100000,
                'kategori' => 'Lainnya',
                'status' => true
            ],
            [
                'nama' => 'Ujian Semester',
                'nominal' => 75000,
                'kategori' => 'Lainnya',
                'status' => true
            ]
        ];

        foreach ($jenisPembayaran as $jenis) {
            JenisPembayaran::create($jenis);
        }

        // Create sample pembayaran
        $siswa = User::where('role', 'siswa')->get();
        $jenis = JenisPembayaran::all();

        foreach ($siswa as $s) {
            foreach ($jenis as $j) {
                if (rand(0, 1)) {
                    Pembayaran::create([
                        'user_id' => $s->id,
                        'jenis_pembayaran_id' => $j->id,
                        'tanggal_bayar' => now()->subDays(rand(1, 30)),
                        'bukti' => 'bukti_example.jpg',
                        'keterangan' => 'Pembayaran ' . $j->nama,
                        'status' => ['pending', 'approved', 'rejected'][rand(0, 2)]
                    ]);
                }
            }
        }
    }
}