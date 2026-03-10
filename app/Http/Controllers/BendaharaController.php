<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pembayaran;
use App\Models\JenisPembayaran;
use App\Models\Penagihan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKeuanganExport;

class BendaharaController extends Controller
{
    public function dashboard()
    {
        $totalPemasukan = Pembayaran::where('pembayarans.status', 'approved')
            ->join('jenis_pembayarans', 'pembayarans.jenis_pembayaran_id', '=', 'jenis_pembayarans.id')
            ->sum('jenis_pembayarans.nominal');
            
        $pendingCount = Pembayaran::where('pembayarans.status', 'pending')->count();
        $approvedCount = Pembayaran::where('pembayarans.status', 'approved')->count();
        $rejectedCount = Pembayaran::where('pembayarans.status', 'rejected')->count();

        // Pembayaran mendekati tenggat waktu
        $pembayaranMendekatiTenggat = Pembayaran::where('status', 'pending')
            ->where('tenggat_waktu', '<=', Carbon::now()->addDays(3))
            ->where('tenggat_waktu', '>=', Carbon::now())
            ->count();

        // Pembayaran terlambat
        $pembayaranTerlambat = Pembayaran::where('status', 'pending')
            ->where('tenggat_waktu', '<', Carbon::now())
            ->count();

        // Data untuk chart
        $chartData = $this->getChartData();

        return view('bendahara.dashboard', compact(
            'totalPemasukan', 
            'pendingCount', 
            'approvedCount', 
            'rejectedCount',
            'pembayaranMendekatiTenggat',
            'pembayaranTerlambat',
            'chartData'
        ));
    }

    /**
     * Get data for charts
     */
    private function getChartData()
    {
        // Data untuk 6 bulan terakhir
        $months = [];
        $pemasukanData = [];
        $pembayaranData = [];
        $siswaBaruData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M Y');
            $months[] = $monthName;
            
            // Total pemasukan per bulan
            $pemasukan = Pembayaran::where('pembayarans.status', 'approved')
                ->join('jenis_pembayarans', 'pembayarans.jenis_pembayaran_id', '=', 'jenis_pembayarans.id')
                ->whereYear('pembayarans.updated_at', $month->year)
                ->whereMonth('pembayarans.updated_at', $month->month)
                ->sum('jenis_pembayarans.nominal');
            
            $pemasukanData[] = $pemasukan;
            
            // Jumlah pembayaran per bulan
            $pembayaranCount = Pembayaran::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $pembayaranData[] = $pembayaranCount;

            // Siswa baru per bulan
            $siswaBaru = User::where('role', 'siswa')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $siswaBaruData[] = $siswaBaru;
        }

        // Data status pembayaran
        $statusData = [
            'approved' => Pembayaran::where('pembayarans.status', 'approved')->count(),
            'pending' => Pembayaran::where('pembayarans.status', 'pending')->count(),
            'rejected' => Pembayaran::where('pembayarans.status', 'rejected')->count(),
        ];

        // Data pembayaran per jenis
        $jenisPembayaranData = JenisPembayaran::withCount(['pembayarans' => function($query) {
            $query->where('pembayarans.status', 'approved');
        }])
        ->where('jenis_pembayarans.status', true)
        ->get()
        ->map(function($jenis) {
            return [
                'nama' => $jenis->nama,
                'jumlah' => $jenis->pembayarans_count,
                'total' => $jenis->pembayarans()->where('pembayarans.status', 'approved')->count() * $jenis->nominal
            ];
        });

        // Data siswa per kelas
        $siswaPerKelas = User::where('role', 'siswa')
            ->select('kelas', DB::raw('COUNT(*) as total'))
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get()
            ->map(function($item) {
                return [
                    'kelas' => 'Kelas ' . $item->kelas,
                    'total' => $item->total
                ];
            });

        // Data siswa per jurusan
        $siswaPerJurusan = User::where('role', 'siswa')
            ->select('jurusan', DB::raw('COUNT(*) as total'))
            ->groupBy('jurusan')
            ->get()
            ->map(function($item) {
                return [
                    'jurusan' => $item->jurusan,
                    'total' => $item->total
                ];
            });

        // Status pembayaran siswa
        $totalSiswa = User::where('role', 'siswa')->count();
        $siswaLunas = $this->getSiswaLunasCount();
        $siswaBelumLunas = $totalSiswa - $siswaLunas;

        // Top 5 siswa dengan pembayaran terbanyak
        $topSiswa = User::where('role', 'siswa')
            ->withSum(['pembayarans as total_pembayaran' => function($query) {
                $query->where('pembayarans.status', 'approved')
                      ->join('jenis_pembayarans', 'pembayarans.jenis_pembayaran_id', '=', 'jenis_pembayarans.id')
                      ->select(DB::raw('COALESCE(SUM(jenis_pembayarans.nominal), 0)'));
            }], 'jenis_pembayarans.nominal')
            ->orderBy('total_pembayaran', 'desc')
            ->limit(5)
            ->get(['id', 'name', 'nis', 'kelas', 'jurusan', 'total_pembayaran']);

        return [
            'months' => $months,
            'pemasukan' => $pemasukanData,
            'pembayaran_count' => $pembayaranData,
            'siswa_baru' => $siswaBaruData,
            'status' => $statusData,
            'jenis_pembayaran' => $jenisPembayaranData,
            'siswa_per_kelas' => $siswaPerKelas,
            'siswa_per_jurusan' => $siswaPerJurusan,
            'status_siswa' => [
                'lunas' => $siswaLunas,
                'belum_lunas' => $siswaBelumLunas,
                'total' => $totalSiswa
            ],
            'top_siswa' => $topSiswa
        ];
    }

    /**
     * Get count of students who have paid all bills
     */
    private function getSiswaLunasCount()
    {
        $totalJenisPembayaran = JenisPembayaran::where('status', true)->count();
        
        if ($totalJenisPembayaran == 0) {
            return 0;
        }

        $siswaLunas = User::where('role', 'siswa')
            ->withCount(['pembayarans as approved_count' => function($query) {
                $query->where('pembayarans.status', 'approved');
            }])
            ->get()
            ->filter(function($user) use ($totalJenisPembayaran) {
                return $user->approved_count >= $totalJenisPembayaran;
            })
            ->count();

        return $siswaLunas;
    }

    /**
     * APPROVAL PEMBAYARAN
     */
    public function approvalPembayaran(Request $request)
    {
        if ($request->ajax()) {
            $queryCommon = Pembayaran::query()
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
                });

            $data = (clone $queryCommon)
                ->when($request->kategori, function($query) use ($request) {
                    return $query->whereHas('jenisPembayaran', function($q) use ($request) {
                        $q->where('kategori', $request->kategori);
                    });
                })
                ->when($request->status, function($query) use ($request) {
                    return $query->where('pembayarans.status', $request->status);
                });

            $statusCountQuery = (clone $queryCommon)
                ->when($request->kategori, function($query) use ($request) {
                    return $query->whereHas('jenisPembayaran', function($q) use ($request) {
                        $q->where('kategori', $request->kategori);
                    });
                });

            $kategoriCountQuery = (clone $queryCommon)
                ->when($request->status, function($query) use ($request) {
                    return $query->where('pembayarans.status', $request->status);
                });

            $dataTableQuery = (clone $data)
                ->with(['user', 'jenisPembayaran'])
                ->select('pembayarans.*')
                ->orderBy('pembayarans.created_at', 'desc');

            $totalCount = (clone $statusCountQuery)->count();
            $pendingCount = (clone $statusCountQuery)->where('status', 'pending')->count();
            $approvedCount = (clone $statusCountQuery)->where('status', 'approved')->count();
            $rejectedCount = (clone $statusCountQuery)->where('status', 'rejected')->count();

            $kategoriCounts = (clone $kategoriCountQuery)
                ->join('jenis_pembayarans', 'pembayarans.jenis_pembayaran_id', '=', 'jenis_pembayarans.id')
                ->selectRaw('jenis_pembayarans.kategori as kategori, COUNT(*) as total')
                ->groupBy('jenis_pembayarans.kategori')
                ->pluck('total', 'kategori')
                ->toArray();

            return DataTables::of($dataTableQuery)
                ->addIndexColumn()
                ->addColumn('nama_siswa', function($row){
                    return $row->user->name ?? '-';
                })
                ->addColumn('nis', function($row){
                    return $row->user->nis ?? '-';
                })
                ->addColumn('kelas_jurusan', function($row){
                    return ($row->user->kelas ?? '-') . ' ' . ($row->user->jurusan ?? '-');
                })
                ->addColumn('jenis_pembayaran', function($row){
                    return $row->jenisPembayaran->nama ?? '-';
                })
                ->addColumn('status_badge', function($row){
                    $badgeClass = [
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger'
                    ][$row->status] ?? 'secondary';
                    
                    return '<span class="badge badge-' . $badgeClass . '">' . strtoupper($row->status) . '</span>';
                })
                ->addColumn('nominal', function($row){
                    return $row->jenisPembayaran ? 'Rp ' . number_format($row->jenisPembayaran->nominal, 0, ',', '.') : '-';
                })
                ->addColumn('tenggat_badge', function($row){
                    if (!$row->tenggat_waktu) return '-';
                    
                    $now = Carbon::now();
                    $tenggat = Carbon::parse($row->tenggat_waktu);
                    
                    if ($tenggat->lt($now)) {
                        return '<span class="badge badge-danger">Terlambat</span>';
                    } elseif ($tenggat->diffInDays($now) <= 3) {
                        return '<span class="badge badge-warning">Mendekati</span>';
                    } else {
                        return '<span class="badge badge-success">Aman</span>';
                    }
                })
                ->addColumn('hari_tersisa', function($row){
                    if (!$row->tenggat_waktu) return '-';
                    
                    $now = Carbon::now();
                    $tenggat = Carbon::parse($row->tenggat_waktu);
                    
                    if ($tenggat->lt($now)) {
                        return '0 hari';
                    } else {
                        return $tenggat->diffInDays($now) . ' hari';
                    }
                })
                ->addColumn('bukti', function($row){
                     if ($row->bukti) {
                        $url = asset('storage/'.$row->bukti);
                        return '<button type="button" class="btn btn-sm btn-primary view-bukti-btn" data-url="'.$url.'" title="Lihat Bukti"><i class="fas fa-eye"></i> Lihat</button>';
                     }
                     return '<span class="text-muted">Tidak ada</span>';
                })
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group">';
                    
                    if ($row->status == 'pending') {
                        $btn .= '<button class="btn btn-success btn-sm approve-btn" data-id="'.$row->id.'" title="Setujui">
                                    <i class="fas fa-check"></i>
                                </button>';
                        $btn .= '<button class="btn btn-danger btn-sm reject-btn" data-id="'.$row->id.'" title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>';
                    }
                    
                    $btn .= '<a href="'.route('bendahara.pembayaran.show', $row->id).'" class="btn btn-info btn-sm" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>';
                    $btn .= '</div>';
                    
                    return $btn;
                })
                ->rawColumns(['status_badge', 'tenggat_badge', 'bukti', 'action'])
                ->with([
                    'recordsTotal' => $totalCount,
                    'recordsFiltered' => $data->count(),
                    'pendingCount' => $pendingCount,
                    'approvedCount' => $approvedCount,
                    'rejectedCount' => $rejectedCount,
                    'kategoriCounts' => $kategoriCounts,
                ])
                ->make(true);
        }

        $kelas = ['10', '11', '12'];
        $jurusan = ['IPA', 'IPS'];
        $jenisPembayaran = JenisPembayaran::where('status', true)->get();

        return view('bendahara.approval-pembayaran', compact('kelas', 'jurusan', 'jenisPembayaran'));
    }

    public function showPembayaran($id)
    {
        $pembayaran = Pembayaran::with(['user', 'jenisPembayaran'])->findOrFail($id);
        
        return view('bendahara.show-pembayaran', compact('pembayaran'));
    }

    public function approvePembayaran(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $pembayaran = Pembayaran::findOrFail($id);
            $pembayaran->update([
                'status' => 'approved',
                'alasan_reject' => null,
                'keterangan_admin' => $request->keterangan_admin,
                'approved_at' => Carbon::now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil disetujui!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving payment: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rejectPembayaran(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string|max:500',
            'keterangan_admin' => 'nullable|string|max:500'
        ]);

        try {
            $pembayaran = Pembayaran::findOrFail($id);
            $pembayaran->update([
                'status' => 'rejected',
                'alasan_reject' => $request->alasan_reject,
                'keterangan_admin' => $request->keterangan_admin
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil ditolak!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error rejecting payment: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * MANAJEMEN DATA SISWA
     */
    public function dataSiswa(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'siswa')
                ->whereNull('deleted_at')
                ->when($request->kelas, function($query) use ($request) {
                    return $query->where('kelas', $request->kelas);
                })
                ->when($request->jurusan, function($query) use ($request) {
                    return $query->where('jurusan', $request->jurusan);
                })
                ->select(['id', 'name', 'nis', 'email', 'kelas', 'jurusan', 'foto', 'created_at']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('foto', function($row){
                    if($row->foto){
                        return '<img src="'.asset('storage/'.$row->foto).'" class="img-circle elevation-2" style="width: 35px; height: 35px; object-fit: cover;">';
                    }
                    return '<i class="fas fa-user-circle fa-2x text-secondary"></i>';
                })
                ->addColumn('total_pembayaran', function($row){
                    $total = Pembayaran::where('user_id', $row->id)
                        ->where('pembayarans.status', 'approved')
                        ->join('jenis_pembayarans', 'pembayarans.jenis_pembayaran_id', '=', 'jenis_pembayarans.id')
                        ->sum('jenis_pembayarans.nominal');
                    return 'Rp ' . number_format($total, 0, ',', '.');
                })
                ->addColumn('total_tagihan', function($row){
                    $total = JenisPembayaran::where('status', true)->sum('nominal');
                    return 'Rp ' . number_format($total, 0, ',', '.');
                })
                ->addColumn('status_pembayaran', function($row){
                    $totalJenis = JenisPembayaran::where('status', true)->count();
                    $totalBayar = Pembayaran::where('user_id', $row->id)
                        ->where('pembayarans.status', 'approved')
                        ->count();
                    
                    if ($totalBayar == 0) {
                        return '<span class="badge badge-danger">Belum Bayar</span>';
                    } elseif ($totalBayar < $totalJenis) {
                        return '<span class="badge badge-warning">Sebagian</span>';
                    } else {
                        return '<span class="badge badge-success">Lunas</span>';
                    }
                })
                ->addColumn('action', function($row){
                    return '
                        <div class="btn-group">
                            <a href="'.route('bendahara.data-siswa.show', $row->id).'" class="btn btn-info btn-sm" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-warning btn-sm edit-siswa" data-id="'.$row->id.'" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['foto', 'status_pembayaran', 'action'])
                ->make(true);
        }

        $kelas = ['10', '11', '12'];
        $jurusan = ['IPA', 'IPS'];

        return view('bendahara.data-siswa', compact('kelas', 'jurusan'));
    }

    /**
     * STORE NEW SISWA
     */
    public function storeSiswa(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'kelas' => 'required|in:10,11,12',
            'jurusan' => 'required|in:IPA,IPS',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'nis' => $request->nis,
                'email' => $request->email,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
                'email_verified_at' => Carbon::now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil dibuat!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating student: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * SHOW DETAIL SISWA
     */
    public function showSiswa($id)
    {
        $siswa = User::with(['pembayarans' => function($query) {
            $query->with('jenisPembayaran')->orderBy('created_at', 'desc');
        }])
        ->where('role', 'siswa')
        ->whereNull('deleted_at')
        ->findOrFail($id);

        $totalPembayaran = $siswa->pembayarans->where('status', 'approved')->sum(function($pembayaran) {
            return $pembayaran->jenisPembayaran->nominal ?? 0;
        });

        $jenisPembayaran = JenisPembayaran::where('status', true)->get();
        $totalTagihan = $jenisPembayaran->sum('nominal');

        // Hitung progress pembayaran
        $progress = $totalTagihan > 0 ? ($totalPembayaran / $totalTagihan) * 100 : 0;

        return view('bendahara.show-siswa', compact(
            'siswa', 
            'totalPembayaran', 
            'totalTagihan', 
            'jenisPembayaran',
            'progress'
        ));
    }

    /**
     * EDIT SISWA
     */
    public function editSiswa($id)
    {
        try {
            $siswa = User::where('role', 'siswa')
                        ->whereNull('deleted_at')
                        ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'siswa' => $siswa
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading student data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data siswa'
            ], 500);
        }
    }

    /**
     * UPDATE SISWA
     */
    public function updateSiswa(Request $request, $id)
    {
        $siswa = User::where('role', 'siswa')
                    ->whereNull('deleted_at')
                    ->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|max:20|unique:users,nis,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'kelas' => 'required|in:10,11,12',
            'jurusan' => 'required|in:IPA,IPS',
            'password' => 'nullable|confirmed|min:6',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'name' => $request->name,
                'nis' => $request->nis,
                'email' => $request->email,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $siswa->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating student: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE SISWA dengan soft delete
     */
    public function destroySiswa($id)
    {
        return response()->json([
            'success' => false,
            'message' => 'Akses ditolak. Bendahara tidak memiliki izin untuk menghapus data siswa.'
        ], 403);
    }

    /**
     * MANAJEMEN JENIS PEMBAYARAN - FIXED METHODS
     */
    public function jenisPembayaran(Request $request)
    {
        if ($request->ajax()) {
            $data = JenisPembayaran::select('*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nominal_formatted', function($row) {
                    return 'Rp ' . number_format($row->nominal, 0, ',', '.');
                })
                ->addColumn('status_badge', function($row) {
                    return $row->status ? 
                        '<span class="badge badge-success">Aktif</span>' : 
                        '<span class="badge badge-danger">Nonaktif</span>';
                })
                ->addColumn('total_pembayaran', function($row) {
                    return $row->pembayarans()->where('status', 'approved')->count();
                })
                ->addColumn('total_pemasukan', function($row) {
                    $total = $row->pembayarans()->where('status', 'approved')->count() * $row->nominal;
                    return 'Rp ' . number_format($total, 0, ',', '.');
                })
                ->addColumn('action', function($row) {
                    return '
                        <div class="btn-group">
                            <a href="'.route('bendahara.jenis-pembayaran.show', $row->id).'" class="btn btn-info btn-sm" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-warning btn-sm edit-jenis" data-id="'.$row->id.'" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-jenis" data-id="'.$row->id.'" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        // Add statistics data for the view
        $totalJenisAktif = JenisPembayaran::where('status', true)->count();
        $totalPembayaran = Pembayaran::where('status', 'approved')->count();
        $totalPemasukan = Pembayaran::where('pembayarans.status', 'approved')
            ->join('jenis_pembayarans', 'pembayarans.jenis_pembayaran_id', '=', 'jenis_pembayarans.id')
            ->sum('jenis_pembayarans.nominal');

        return view('bendahara.jenis-pembayaran', compact(
            'totalJenisAktif',
            'totalPembayaran',
            'totalPemasukan'
        ));
    }

    /**
     * EDIT JENIS PEMBAYARAN - Show edit form
     */
    public function editJenisPembayaran($id)
    {
        try {
            $jenis = JenisPembayaran::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'jenis' => $jenis
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading jenis pembayaran data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data jenis pembayaran'
            ], 500);
        }
    }

    /**
     * UPDATE JENIS PEMBAYARAN
     */
    public function updateJenisPembayaran(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:500',
            'status' => 'required|boolean'
        ]);

        try {
            DB::beginTransaction();

            $jenis = JenisPembayaran::findOrFail($id);
            $jenis->update([
                'nama' => $request->nama,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'status' => $request->status
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Jenis pembayaran berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating jenis pembayaran: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * LAPORAN KEUANGAN
     */
    public function laporanKeuangan(Request $request)
    {
        $query = Pembayaran::with(['user', 'jenisPembayaran'])
            ->where('status', 'approved');

        // Filter bulan
        if ($request->bulan) {
            $query->whereMonth('tanggal_bayar', $request->bulan);
        }

        // Filter tahun
        if ($request->tahun) {
            $query->whereYear('tanggal_bayar', $request->tahun);
        }

        // Filter jenis pembayaran
        if ($request->jenis_pembayaran) {
            $query->where('jenis_pembayaran_id', $request->jenis_pembayaran);
        }

        $pembayaran = $query->orderBy('tanggal_bayar', 'desc')->get();
        $totalPemasukan = $pembayaran->sum('jumlah_bayar');
        $jenisPembayaran = JenisPembayaran::where('status', true)->get();
        
        return view('bendahara.laporan-keuangan.index', compact('pembayaran', 'totalPemasukan', 'jenisPembayaran'));
    }

    /**
 * EXPORT LAPORAN KEUANGAN
 */
public function exportLaporanKeuanganPDF(Request $request)
    {
        try {
            \Log::info('Export PDF started', $request->all());

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

            $totalPemasukan = $pembayaran->sum(function($item) {
                return $item->jenisPembayaran->nominal ?? 0;
            });

            \Log::info('Data retrieved', [
                'count' => $pembayaran->count(),
                'total' => $totalPemasukan
            ]);

            $data = [
                'pembayaran' => $pembayaran,
                'totalPemasukan' => $totalPemasukan,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'tanggal_cetak' => Carbon::now()->format('d/m/Y H:i:s'),
                'user' => auth()->user()
            ];

            // PERBAIKAN: Gunakan PDF facade dengan benar
            $pdf = PDF::loadView('bendahara.laporan-keuangan.pdf', $data)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'dpi' => 150
                ]);

            \Log::info('PDF generated successfully');

            return $pdf->download('laporan-keuangan-' . date('Y-m-d') . '.pdf');

        } catch (\Exception $e) {
            \Log::error('Error exporting PDF: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    /**
     * EXPORT LAPORAN KEUANGAN EXCEL
     */
    public function exportLaporanKeuanganExcel(Request $request)
    {
        return Excel::download(new LaporanKeuanganExport($request), 'laporan-keuangan-' . date('Y-m-d') . '.xlsx');
    }

/**
 * GENERATE EXCEL
 */
private function generateExcel($pembayaran, $totalPemasukan, $startDate, $endDate)
{
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set header
    $sheet->setCellValue('A1', 'LAPORAN KEUANGAN');
    $sheet->setCellValue('A2', 'Periode: ' . $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'));
    $sheet->setCellValue('A3', 'Total Pemasukan: Rp ' . number_format($totalPemasukan, 0, ',', '.'));
    
    // Set column headers
    $sheet->setCellValue('A5', 'No');
    $sheet->setCellValue('B5', 'Tanggal');
    $sheet->setCellValue('C5', 'Nama Siswa');
    $sheet->setCellValue('D5', 'NIS');
    $sheet->setCellValue('E5', 'Kelas');
    $sheet->setCellValue('F5', 'Jurusan');
    $sheet->setCellValue('G5', 'Jenis Pembayaran');
    $sheet->setCellValue('H5', 'Nominal');

    // Style untuk header
    $headerStyle = [
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E6E6FA']
        ]
    ];
    $sheet->getStyle('A5:H5')->applyFromArray($headerStyle);

    // Isi data
    $row = 6;
    $no = 1;
    foreach ($pembayaran as $item) {
        $sheet->setCellValue('A' . $row, $no++);
        $sheet->setCellValue('B' . $row, $item->updated_at->format('d/m/Y H:i'));
        $sheet->setCellValue('C' . $row, $item->user->name ?? '-');
        $sheet->setCellValue('D' . $row, $item->user->nis ?? '-');
        $sheet->setCellValue('E' . $row, $item->user->kelas ?? '-');
        $sheet->setCellValue('F' . $row, $item->user->jurusan ?? '-');
        $sheet->setCellValue('G' . $row, $item->jenisPembayaran->nama ?? '-');
        $sheet->setCellValue('H' . $row, $item->nominal);
        $row++;
    }

    // Total
    $sheet->setCellValue('G' . $row, 'TOTAL:');
    $sheet->setCellValue('H' . $row, $totalPemasukan);
    $sheet->getStyle('G' . $row . ':H' . $row)->applyFromArray($headerStyle);

    // Auto size columns
    foreach (range('A', 'H') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = 'laporan-keuangan-' . $startDate->format('Y-m-d') . '-sd-' . $endDate->format('Y-m-d') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}

/**
 * GENERATE PDF
 */
/**
 * GENERATE PDF - Versi Sederhana
 */
private function generatePDF($pembayaran, $totalPemasukan, $startDate, $endDate)
{
    // Gunakan view yang sudah ada atau buat inline HTML
    $html = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .header { text-align: center; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #000; padding: 8px; text-align: left; }
            th { background-color: #f0f0f0; }
            .total { font-weight: bold; }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>LAPORAN KEUANGAN</h1>
            <p>Periode: ' . $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y') . '</p>
            <p>Total Pemasukan: Rp ' . number_format($totalPemasukan, 0, ',', '.') . '</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Jenis Bayar</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>';

    $no = 1;
    foreach ($pembayaran as $item) {
        $html .= '
                <tr>
                    <td>' . $no++ . '</td>
                    <td>' . $item->updated_at->format('d/m/Y') . '</td>
                    <td>' . ($item->user->name ?? '-') . '</td>
                    <td>' . ($item->user->kelas ?? '-') . '</td>
                    <td>' . ($item->jenisPembayaran->nama ?? '-') . '</td>
                    <td>Rp ' . number_format($item->nominal, 0, ',', '.') . '</td>
                </tr>';
    }

    $html .= '
            </tbody>
            <tfoot>
                <tr class="total">
                    <td colspan="5">TOTAL</td>
                    <td>Rp ' . number_format($totalPemasukan, 0, ',', '.') . '</td>
                </tr>
            </tfoot>
        </table>
    </body>
    </html>';

    $pdf = PDF::loadHTML($html);
    $filename = 'laporan-keuangan-' . $startDate->format('Y-m-d') . '-sd-' . $endDate->format('Y-m-d') . '.pdf';

    return $pdf->download($filename);
}

/**
 * Destroy Jenis Pembayaran
 */


    /**
     * STORE JENIS PEMBAYARAN
     */
    public function storeJenisPembayaran(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1000',
            'kategori' => 'required|string|in:SPP,Gedung,Praktikum,Ujian,Lainnya',
            'keterangan' => 'nullable|string|max:500',
            'status' => 'nullable'
        ]);

        try {
            DB::beginTransaction();

            JenisPembayaran::create([
                'nama' => $request->nama,
                'nominal' => $request->nominal,
                'kategori' => $request->kategori,
                'keterangan' => $request->keterangan ?? null,
                'status' => $request->has('status') ? 1 : 0
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Jenis pembayaran berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating payment type: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Gagal menambahkan jenis pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * SHOW JENIS PEMBAYARAN
     */
    public function showJenisPembayaran($id)
    {
        try {
            $jenisPembayaran = JenisPembayaran::with(['pembayarans' => function($query) {
                $query->with('user')
                      ->where('status', 'approved')
                      ->latest();
            }])->findOrFail($id);

            $totalPemasukan = 0;
            $pembayarans = $jenisPembayaran->pembayarans ?? collect();
            
            foreach ($pembayarans as $pembayaran) {
                if ($pembayaran && $jenisPembayaran) {
                    $totalPemasukan += $jenisPembayaran->nominal;
                }
            }

            $totalSiswa = User::where('role', 'siswa')->count();
            $totalBayar = $pembayarans->count();

            // Note: Reuse admin view or create a specific one for bendahara.
            // Using admin view for now as it likely contains generic display logic suitable for bendahara too,
            // or we could point to a bendahara specific view if it exists.
            // Given the file list earlier only showed bendahara/jenis-pembayaran.blade.php, let's assume we might need a show view.
            // If admin view is accessible, good. If not, we might need to duplicate/alias it.
            // Checking AdminController, it returns 'admin.show-jenis-pembayaran'.
            // Let's assume we can use the same view or we should check if 'bendahara.show-jenis-pembayaran' exists.
            // Based on list_dir earlier, 'bendahara/show-jenis-pembayaran.blade.php' was NOT in the list.
            // So we might need to rely on 'admin.show-jenis-pembayaran' or create a new one.
            // For safety, let's try to use 'admin.show-jenis-pembayaran' IF user has access, 
            // BUT usually views are not restricted by role, only routes.
            // However, the menu links inside the view might point to admin routes.
            // Ideally we need 'bendahara.show-jenis-pembayaran'.
            // Let's first check if 'admin.show-jenis-pembayaran' exists.
            
            return view('bendahara.show-jenis-pembayaran', compact(
                'jenisPembayaran', 
                'totalPemasukan', 
                'totalSiswa', 
                'totalBayar'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error showing payment type: ' . $e->getMessage());
            return redirect()->route('bendahara.jenis-pembayaran.index')
                ->with('error', 'Jenis pembayaran tidak ditemukan.');
        }
    }

    /**
     * DESTROY JENIS PEMBAYARAN
     */
    public function destroyJenisPembayaran($id)
    {
        try {
            // Cek apakah data ada
            $jenisPembayaran = JenisPembayaran::find($id);
            
            if (!$jenisPembayaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data jenis pembayaran tidak ditemukan'
                ], 404);
            }

            DB::beginTransaction();

            // Cek apakah ada pembayaran yang menggunakan jenis ini
            $pembayaranCount = $jenisPembayaran->pembayarans()->count();
            
            if ($pembayaranCount > 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus karena sudah digunakan dalam ' . $pembayaranCount . ' transaksi'
                ], 422);
            }

            $jenisPembayaran->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Jenis pembayaran berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting payment type: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

}