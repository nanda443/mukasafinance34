<?php
// app/Http/Controllers/LaporanController.php
namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\JenisPembayaran;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPemasukanExport;
use PDF;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $laporan = $this->getLaporanData($request);
        
        // Hitung total dengan cara yang aman
        $total = $laporan->sum(function($item) {
            return $item->jenisPembayaran->nominal ?? 0;
        });

        $jenisPembayaran = JenisPembayaran::where('status', true)->get();
        $kelas = ['10', '11', '12'];
        $jurusan = ['IPA', 'IPS'];

        return view('laporan.index', compact('laporan', 'total', 'jenisPembayaran', 'kelas', 'jurusan'));
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with(['user', 'jenisPembayaran'])
            ->where('pembayarans.status', 'approved')
            ->findOrFail($id);

        return view('laporan.show', compact('pembayaran'));
    }

      public function exportPDF(Request $request)
    {
        try {
            $laporan = $this->getLaporanData($request);
            
            $total = $laporan->sum(function($item) {
                return $item->jenisPembayaran->nominal ?? 0;
            });

            $data = [
                'laporan' => $laporan,
                'total' => $total,
                'tanggal_awal' => $request->tanggal_awal ? Carbon::parse($request->tanggal_awal)->format('d/m/Y') : '-',
                'tanggal_akhir' => $request->tanggal_akhir ? Carbon::parse($request->tanggal_akhir)->format('d/m/Y') : '-',
                'kelas' => $request->kelas ?: 'Semua Kelas',
                'jurusan' => $request->jurusan ?: 'Semua Jurusan',
                'jenis_pembayaran' => $request->jenis_pembayaran ? (JenisPembayaran::find($request->jenis_pembayaran)->nama ?? 'Tidak Diketahui') : 'Semua Jenis',
                'total_transaksi' => $laporan->count(),
                'tanggal_cetak' => Carbon::now()->format('d/m/Y H:i:s'),
                'user_role' => auth()->user()->role
            ];

            // GUNAKAN \PDF:: (dengan backslash)
            $pdf = \PDF::loadView('laporan.pdf', $data)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'dpi' => 150
                ]);

            return $pdf->download('laporan-pemasukan-' . date('Y-m-d-H-i-s') . '.pdf');

        } catch (\Exception $e) {
            \Log::error('Error exporting PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new LaporanPemasukanExport($request), 'laporan-pemasukan-' . date('Y-m-d') . '.xlsx');
    }

    private function getLaporanData(Request $request)
    {
        // Gunakan whereHas untuk memastikan relasi ada
        return Pembayaran::with(['user', 'jenisPembayaran'])
            ->where('pembayarans.status', 'approved')
            ->whereHas('user') // Pastikan user masih ada
            ->whereHas('jenisPembayaran') // Pastikan jenis pembayaran masih ada
            ->when($request->tanggal_awal, function($query) use ($request) {
                return $query->whereDate('pembayarans.tanggal_bayar', '>=', $request->tanggal_awal);
            })
            ->when($request->tanggal_akhir, function($query) use ($request) {
                return $query->whereDate('pembayarans.tanggal_bayar', '<=', $request->tanggal_akhir);
            })
            ->when($request->kelas, function($query) use ($request) {
                return $query->whereHas('user', function($q) use ($request) {
                    $q->where('kelas', $request->kelas);
                });
            })
            ->when($request->jurusan, function($query) use ($request) {
                return $query->whereHas('user', function($q) use ($request) {
                    $q->where('jurusan', $request->jurusan);
                });
            })
            ->when($request->jenis_pembayaran, function($query) use ($request) {
                return $query->where('jenis_pembayaran_id', $request->jenis_pembayaran);
            })
            ->orderBy('pembayarans.tanggal_bayar', 'desc')
            ->get();
   }

   /**
     * Menampilkan laporan keuangan khusus bendahara
     */
    public function laporanKeuangan(Request $request)
    {
        // Logika khusus untuk laporan keuangan bendahara
        $pembayaran = Pembayaran::with(['user', 'jenisPembayaran'])
            ->where('status', 'approved')
            ->when($request->bulan, function($query) use ($request) {
                return $query->whereMonth('tanggal_bayar', $request->bulan);
            })
            ->when($request->tahun, function($query) use ($request) {
                return $query->whereYear('tanggal_bayar', $request->tahun);
            })
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        $totalPemasukan = $pembayaran->sum('jumlah_bayar');
        
        return view('bendahara.laporan-keuangan.index', compact('pembayaran', 'totalPemasukan'));
    }

    /**
     * Export PDF laporan keuangan
     */
    public function exportLaporanKeuanganPDF(Request $request)
    {
        $pembayaran = Pembayaran::with(['user', 'jenisPembayaran'])
            ->where('status', 'approved')
            ->when($request->bulan, function($query) use ($request) {
                return $query->whereMonth('tanggal_bayar', $request->bulan);
            })
            ->when($request->tahun, function($query) use ($request) {
                return $query->whereYear('tanggal_bayar', $request->tahun);
            })
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        $totalPemasukan = $pembayaran->sum('jumlah_bayar');

        $data = [
            'pembayaran' => $pembayaran,
            'totalPemasukan' => $totalPemasukan,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ];

        $pdf = PDF::loadView('bendahara.laporan-keuangan.pdf', $data);
        return $pdf->download('laporan-keuangan-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Excel laporan keuangan
     */
    public function exportLaporanKeuanganExcel(Request $request)
    {
        return Excel::download(new LaporanKeuanganExport($request), 'laporan-keuangan-' . date('Y-m-d') . '.xlsx');
    }
   
}