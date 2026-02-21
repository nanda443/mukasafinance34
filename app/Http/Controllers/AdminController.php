<?php
// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use App\Exports\SiswaTemplateExport;

class AdminController extends Controller
{
    public function dashboard()
    {
        try {
            $totalSiswa = User::where('role', 'siswa')->count();
            
            // Hitung total pemasukan dari pembayaran yang sudah disetujui
            $totalPembayaran = Pembayaran::where('status', 'approved')
                ->selectRaw('SUM(jenis_pembayarans.nominal) as total')
                ->join('jenis_pembayarans', 'pembayarans.jenis_pembayaran_id', '=', 'jenis_pembayarans.id')
                ->first()?->total ?? 0;
                
            $pendingPembayaran = Pembayaran::where('status', 'pending')->count();
            
            // Hitung data per bulan untuk chart
            $bulanan = Pembayaran::where('status', 'approved')
                ->selectRaw('MONTH(pembayarans.created_at) as bulan, SUM(jenis_pembayarans.nominal) as total')
                ->join('jenis_pembayarans', 'pembayarans.jenis_pembayaran_id', '=', 'jenis_pembayarans.id')
                ->groupBy('bulan')
                ->get();

            $chartData = array_fill(0, 12, 0);
            foreach ($bulanan as $bulan) {
                $chartData[$bulan->bulan - 1] = (int)$bulan->total;
            }

            return view('admin.dashboard', compact('totalSiswa', 'totalPembayaran', 'pendingPembayaran', 'chartData'));
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return view('admin.dashboard', [
                'totalSiswa' => 0,
                'totalPembayaran' => 0,
                'pendingPembayaran' => 0,
                'chartData' => array_fill(0, 12, 0)
            ]);
        }
    }

    public function dataSiswa(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'siswa')
                ->whereNull('deleted_at')
                ->when($request->global_search, function($query) use ($request) {
                    return $query->where(function($q) use ($request) {
                        $q->where('nis', 'like', '%' . $request->global_search . '%')
                          ->orWhere('name', 'like', '%' . $request->global_search . '%')
                          ->orWhere('email', 'like', '%' . $request->global_search . '%')
                          ->orWhere('kelas', 'like', '%' . $request->global_search . '%')
                          ->orWhere('jurusan', 'like', '%' . $request->global_search . '%');
                    });
                })
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
                            <a href="'.route('admin.data-siswa.show', $row->id).'" class="btn btn-info btn-sm" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-warning btn-sm edit-siswa" data-id="'.$row->id.'" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-siswa" data-id="'.$row->id.'" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['foto', 'status_pembayaran', 'action'])
                ->make(true);
        }

        $kelas = ['10', '11', '12'];
        $jurusan = ['IPA', 'IPS'];

        return view('admin.data-siswa', compact('kelas', 'jurusan'));
    }

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

    public function destroySiswa($id)
    {
        try {
            DB::beginTransaction();

            $siswa = User::where('role', 'siswa')
                        ->whereNull('deleted_at')
                        ->findOrFail($id);
            
            $siswa->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting student: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

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
                'email_verified_at' => now(),
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

    public function showSiswa($id)
    {
        try {
            $siswa = User::with(['pembayarans' => function($query) {
                $query->with('jenisPembayaran')->orderBy('created_at', 'desc');
            }])
            ->where('role', 'siswa')
            ->whereNull('deleted_at')
            ->findOrFail($id);

            $totalPembayaran = $siswa->pembayarans
                ->where('status', 'approved')
                ->sum(function($pembayaran) {
                    return $pembayaran->jenisPembayaran->nominal ?? 0;
                });

            $jenisPembayaran = JenisPembayaran::where('status', true)->get();
            $totalTagihan = $jenisPembayaran->sum('nominal');

            $progress = $totalTagihan > 0 ? ($totalPembayaran / $totalTagihan) * 100 : 0;

            return view('admin.show-siswa', compact(
                'siswa', 
                'totalPembayaran', 
                'totalTagihan', 
                'jenisPembayaran',
                'progress'
            ));

        } catch (\Exception $e) {
            Log::error('Error showing student detail: ' . $e->getMessage());
            return redirect()->route('admin.data-siswa.index')
                ->with('error', 'Data siswa tidak ditemukan.');
        }
    }

    /**
     * JENIS PEMBAYARAN METHODS
     */
    public function jenisPembayaran()
    {
        $jenisPembayaran = JenisPembayaran::withCount(['pembayarans' => function($query) {
            $query->where('status', 'approved');
        }])->get();
        
        return view('admin.jenis-pembayaran', compact('jenisPembayaran'));
    }

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

            return view('admin.show-jenis-pembayaran', compact(
                'jenisPembayaran', 
                'totalPemasukan', 
                'totalSiswa', 
                'totalBayar'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error showing payment type: ' . $e->getMessage());
            return redirect()->route('admin.jenis-pembayaran.index')
                ->with('error', 'Jenis pembayaran tidak ditemukan.');
        }
    }

    public function editJenisPembayaran($id)
    {
        try {
            $jenisPembayaran = JenisPembayaran::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $jenisPembayaran
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading payment type: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data jenis pembayaran'
            ], 500);
        }
    }

    /**
     * UPDATE JENIS PEMBAYARAN - PERBAIKAN
     */
    public function updateJenisPembayaran(Request $request, $id)
    {
        Log::info('=== UPDATE JENIS PEMBAYARAN START ===');
        Log::info('ID: ' . $id);
        Log::info('Request Data: ', $request->all());

        try {
            // Cek apakah data ada
            $jenisPembayaran = JenisPembayaran::find($id);
            
            if (!$jenisPembayaran) {
                Log::warning('JenisPembayaran with ID ' . $id . ' not found');
                return redirect()->route('admin.jenis-pembayaran.index')
                    ->with('error', 'Data jenis pembayaran tidak ditemukan');
            }

            // Validasi data
            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:jenis_pembayarans,nama,' . $id,
                'nominal' => 'required|numeric|min:1000',
                'kategori' => 'required|string|in:SPP,Gedung,Praktikum,Ujian,Lainnya',
                'keterangan' => 'nullable|string|max:500',
                'status' => 'sometimes|boolean'
            ]);

            Log::info('Validation passed');

            DB::beginTransaction();

            // Update data
            $updateData = [
                'nama' => $validated['nama'],
                'nominal' => $validated['nominal'],
                'kategori' => $validated['kategori'],
                'keterangan' => $validated['keterangan'] ?? null,
                'status' => $request->has('status') ? $request->boolean('status') : false
            ];

            Log::info('Update Data: ', $updateData);

            $jenisPembayaran->update($updateData);

            DB::commit();

            Log::info('JenisPembayaran updated successfully: ' . $id);

            return redirect()->route('admin.jenis-pembayaran.index')
                ->with('success', 'Jenis pembayaran berhasil diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $errors = $e->errors();
            Log::error('Validation error: ', $errors);
            
            $firstError = collect($errors)->first()[0] ?? 'Validasi gagal';
            
            return redirect()->route('admin.jenis-pembayaran.index')
                ->with('error', $firstError);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating payment type: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('admin.jenis-pembayaran.index')
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        } finally {
            Log::info('=== UPDATE JENIS PEMBAYARAN END ===');
        }
    }


    /**
     * DELETE JENIS PEMBAYARAN - PERBAIKAN
     */
    public function destroyJenisPembayaran($id)
    {
        Log::info('=== DELETE JENIS PEMBAYARAN START ===');
        Log::info('ID: ' . $id);

        try {
            // Cek apakah data ada
            $jenisPembayaran = JenisPembayaran::find($id);
            
            if (!$jenisPembayaran) {
                Log::warning('JenisPembayaran with ID ' . $id . ' not found');
                return redirect()->route('admin.jenis-pembayaran.index')
                    ->with('error', 'Data jenis pembayaran tidak ditemukan');
            }

            DB::beginTransaction();

            // Cek apakah ada pembayaran yang menggunakan jenis ini
            $pembayaranCount = $jenisPembayaran->pembayarans()->count();
            
            if ($pembayaranCount > 0) {
                DB::rollBack();
                Log::warning('Cannot delete JenisPembayaran ' . $id . ' - used in ' . $pembayaranCount . ' transactions');
                
                return redirect()->route('admin.jenis-pembayaran.index')
                    ->with('error', 'Tidak dapat menghapus jenis pembayaran karena sudah digunakan dalam ' . $pembayaranCount . ' pembayaran.');
            }

            $jenisPembayaran->delete();

            DB::commit();

            Log::info('JenisPembayaran deleted successfully: ' . $id);

            return redirect()->route('admin.jenis-pembayaran.index')
                ->with('success', 'Jenis pembayaran berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting payment type: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('admin.jenis-pembayaran.index')
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        } finally {
            Log::info('=== DELETE JENIS PEMBAYARAN END ===');
        }
    }

    public function importSiswa(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $file = $request->file('file');
            $nama_file = rand() . $file->getClientOriginalName();
            $file->move(public_path('temp_import'), $nama_file);
            $path = public_path('temp_import/' . $nama_file);

            Excel::import(new SiswaImport, $path);
            
            // Hapus file setelah import selesai
            if (file_exists($path)) {
                unlink($path);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil diimport!'
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $messages = '';
            
            foreach ($failures as $failure) {
                $messages .= 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors()) . '<br>';
            }
            
            // Hapus file jika validasi gagal
            if (isset($path) && file_exists($path)) {
                unlink($path);
            }
            
            return response()->json([
                'success' => false,
                'message' => $messages
            ], 422);
        } catch (\Exception $e) {
            // Hapus file jika error
            if (isset($path) && file_exists($path)) {
                unlink($path);
            }

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadTemplateSiswa()
    {
        return Excel::download(new SiswaTemplateExport, 'template_siswa.xlsx');
    }
}