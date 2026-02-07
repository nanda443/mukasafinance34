<?php
// app/Http/Controllers/SiswaController.php
namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\JenisPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $totalBayar = Pembayaran::where('pembayarans.user_id', $user->id)
            ->where('pembayarans.status', 'approved')
            ->join('jenis_pembayarans', 'pembayarans.jenis_pembayaran_id', '=', 'jenis_pembayarans.id')
            ->sum('jenis_pembayarans.nominal');
            
        $pendingCount = Pembayaran::where('pembayarans.user_id', $user->id)
            ->where('pembayarans.status', 'pending')->count();

        $tagihanMendekatiTenggat = Pembayaran::with('jenisPembayaran')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('tenggat_waktu', '<=', Carbon::now()->addDays(3))
            ->where('tenggat_waktu', '>=', Carbon::now())
            ->count();

        $pembayaranTerbaru = Pembayaran::with('jenisPembayaran')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('siswa.dashboard', compact('totalBayar', 'pendingCount', 'tagihanMendekatiTenggat', 'pembayaranTerbaru'));
    }

    public function tagihan()
    {
        $user = Auth::user();
        
        // Jenis pembayaran yang aktif
        $jenisPembayaran = JenisPembayaran::where('status', true)->get();
        
        // Pembayaran yang sudah dilakukan
        $pembayaranSaya = Pembayaran::with('jenisPembayaran')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Tagihan yang belum dibayar (belum ada pembayaran untuk jenis tertentu)
        $tagihanBelumDibayar = $this->getTagihanBelumDibayar($user);

        return view('siswa.tagihan', compact('jenisPembayaran', 'pembayaranSaya', 'tagihanBelumDibayar'));
    }

    public function showTagihan($id)
    {
        $pembayaran = Pembayaran::with('jenisPembayaran')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('siswa.show-tagihan', compact('pembayaran'));
    }

    public function bayar(Request $request)
    {
        $request->validate([
            'jenis_pembayaran_id' => 'required|exists:jenis_pembayarans,id',
            'tanggal_bayar' => 'required|date',
            'bukti' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan' => 'nullable|string|max:500'
        ]);

        $jenisPembayaran = JenisPembayaran::findOrFail($request->jenis_pembayaran_id);

        // Cek apakah sudah ada pembayaran pending atau approved
        $existingPayment = Pembayaran::where('user_id', Auth::id())
            ->where('jenis_pembayaran_id', $request->jenis_pembayaran_id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($existingPayment) {
            return redirect()->back()->with('error', 'Anda sudah melakukan pembayaran untuk tagihan ini (Pending/Lunas).');
        }
        
        // Set tenggat waktu (misalnya 7 hari dari sekarang)
        $tenggatWaktu = now()->addDays(7);

        $file = $request->file('bukti');
        $nama_file = time() . '_' . $file->getClientOriginalName();
        $tujuan_upload = 'uploads/bukti';
        $file->move(public_path('storage/' . $tujuan_upload), $nama_file);
        $buktiPath = $tujuan_upload . '/' . $nama_file;

        Pembayaran::create([
            'user_id' => Auth::id(),
            'jenis_pembayaran_id' => $request->jenis_pembayaran_id,
            'tanggal_bayar' => $request->tanggal_bayar,
            'tenggat_waktu' => $tenggatWaktu,
            'bukti' => $buktiPath,
            'keterangan' => $request->keterangan,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu persetujuan bendahara.');
    }

    private function getTagihanBelumDibayar($user)
    {
        $jenisPembayaran = JenisPembayaran::where('status', true)->get();
        $tagihanBelumDibayar = [];

        foreach ($jenisPembayaran as $jenis) {
            // Cek status pembayaran terakhir
            $pembayaranTerakhir = Pembayaran::where('user_id', $user->id)
                ->where('jenis_pembayaran_id', $jenis->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$pembayaranTerakhir || $pembayaranTerakhir->status == 'rejected') {
                $tagihanBelumDibayar[] = [
                    'jenis_pembayaran' => $jenis,
                    'status' => 'belum_bayar'
                ];
            } elseif ($pembayaranTerakhir->status == 'pending') {
                $tagihanBelumDibayar[] = [
                    'jenis_pembayaran' => $jenis,
                    'status' => 'pending'
                ];
            }
            // Jika status approved, tidak masuk list (sudah lunas)
        }

        return $tagihanBelumDibayar;
    }

    public function profile()
    {
        $user = Auth::user();
        return view('siswa.profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'no_telp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:500',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat
        ];

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        // Handle Foto Upload
        if ($request->hasFile('foto')) {
            $request->validate([
                'foto' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Hapus foto lama jika ada
            if ($user->foto && file_exists(public_path('storage/' . $user->foto))) {
                unlink(public_path('storage/' . $user->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/profile/' . $filename;
            
            // Simpan file ke public/storage/uploads/profile
            $file->move(public_path('storage/uploads/profile'), $filename);
            
            $data['foto'] = $path;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profile berhasil diperbarui');
    }

    public function riwayatPembayaran()
    {
        $pembayarans = Pembayaran::with('jenisPembayaran')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.riwayat-pembayaran', compact('pembayarans'));
    }

    public function showRiwayat($id)
    {
        $pembayaran = Pembayaran::with('jenisPembayaran')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('siswa.show-riwayat', compact('pembayaran'));
    }
}