<?php

namespace App\Http\Controllers;

use App\Models\Penagihan;
use App\Models\User;
use App\Models\Pembayaran;
use App\Models\JenisPembayaran;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PenagihanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Penagihan::select('*')->orderBy('created_at', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nominal_formatted', function($row) {
                    return 'Rp ' . number_format($row->nominal, 0, ',', '.');
                })
                ->addColumn('tenggat_formatted', function($row) {
                    return Carbon::parse($row->tenggat_waktu)->format('d/m/Y');
                })
                ->addColumn('status_badge', function($row) {
                    return $row->status ? 
                        '<span class="badge badge-success">Aktif</span>' : 
                        '<span class="badge badge-secondary">Non-Aktif</span>';
                })
                ->addColumn('target_detail', function($row) {
                    if ($row->target === 'individu') {
                        return '<span class="badge badge-info">Individu</span>';
                    } else {
                        $detail = '<span class="badge badge-primary">Massal</span>';
                        if ($row->kelas) {
                            $detail .= '<br><small>Kelas: ' . $row->kelas . '</small>';
                        }
                        if ($row->jurusan) {
                            $detail .= '<br><small>Jurusan: ' . $row->jurusan . '</small>';
                        }
                        return $detail;
                    }
                })
                ->addColumn('action', function($row) {
                    $btn = '<div class="btn-group">';
                    $btn .= '<a href="'.route('bendahara.penagihan.show', $row->id).'" class="btn btn-info btn-sm" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>';
                    $btn .= '<a href="'.route('bendahara.penagihan.edit', $row->id).'" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>';
                    $btn .= '<button class="btn btn-danger btn-sm delete-penagihan" data-id="'.$row->id.'" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status_badge', 'target_detail', 'action'])
                ->make(true);
        }

        $kelas = ['10', '11', '12'];
        $jurusan = ['IPA', 'IPS'];
        $siswa = User::where('role', 'siswa')->get(['id', 'name', 'nis', 'kelas', 'jurusan']);

        return view('bendahara.penagihan.index', compact('kelas', 'jurusan', 'siswa'));
    }

    public function store(Request $request)
    {
        Log::info('=== STORE PENAGIHAN START ===');
        Log::info('Request data:', $request->all());

        try {
            // Validasi data dengan pesan custom
            $rules = [
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'nominal' => 'required|numeric|min:1000',
                'jenis' => 'required|in:bulanan,tahunan,bebas',
                'target' => 'required|in:massal,individu',
                'tenggat_waktu' => 'required|date|after_or_equal:today',
                'status' => 'required|in:0,1',
            ];

            $messages = [
                'judul.required' => 'Judul penagihan wajib diisi',
                'nominal.required' => 'Nominal wajib diisi',
                'nominal.min' => 'Nominal harus minimal Rp 1.000',
                'jenis.required' => 'Jenis penagihan wajib dipilih',
                'target.required' => 'Target wajib dipilih',
                'tenggat_waktu.required' => 'Tenggat waktu wajib diisi',
                'tenggat_waktu.after_or_equal' => 'Tenggat waktu harus hari ini atau setelahnya',
            ];

            // Tambahkan validasi kelas dan jurusan jika target massal
            if ($request->target === 'massal') {
                $rules['kelas'] = 'required|in:10,11,12';
                $rules['jurusan'] = 'required|in:IPA,IPS';
                $messages['kelas.required'] = 'Kelas wajib dipilih untuk penagihan massal';
                $messages['jurusan.required'] = 'Jurusan wajib dipilih untuk penagihan massal';
            }
            
            // Tambahkan validasi target siswa jika individu
            if ($request->target === 'individu') {
                $rules['target_siswa'] = 'required|array|min:1';
                $rules['target_siswa.*'] = 'exists:users,id';
                $messages['target_siswa.required'] = 'Pilih minimal satu siswa';
                $messages['target_siswa.min'] = 'Pilih minimal satu siswa';
            }

            Log::info('Validation rules:', $rules);
            Log::info('Request target:', ['target' => $request->target]);

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Siapkan data untuk disimpan
            $penagihanData = [
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi ?? null,
                'nominal' => $request->nominal,
                'jenis' => $request->jenis,
                'target' => $request->target,
                'tenggat_waktu' => $request->tenggat_waktu,
                'status' => (int)$request->status,
                'created_by' => auth()->id() ?? null,
                'kelas' => null,
                'jurusan' => null,
                'target_siswa' => null,
            ];

            Log::info('Initial data prepared:', $penagihanData);

            // Tambahkan data berdasarkan target
            if ($request->target === 'massal') {
                $penagihanData['kelas'] = $request->kelas;
                $penagihanData['jurusan'] = $request->jurusan;
                Log::info('Massal target set - Kelas: ' . $request->kelas . ', Jurusan: ' . $request->jurusan);
            } elseif ($request->target === 'individu') {
                $siswaArray = is_array($request->target_siswa) ? $request->target_siswa : [];
                $penagihanData['target_siswa'] = !empty($siswaArray) ? json_encode($siswaArray) : null;
                Log::info('Individu target set - Siswa count: ' . count($siswaArray));
            }

            Log::info('Final data to save:', $penagihanData);

            // Simpan penagihan
            $penagihan = Penagihan::create($penagihanData);

            Log::info('Penagihan created successfully. ID: ' . $penagihan->id);

            // Generate pembayaran untuk siswa yang ditargetkan
            $this->generatePembayaranFromPenagihan($penagihan);

            DB::commit();

            Log::info('=== STORE PENAGIHAN SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Penagihan berhasil dibuat!'
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('Database error: ' . $e->getMessage());
            Log::error('SQL: ' . $e->getSql());
            Log::error('Bindings: ' . json_encode($e->getBindings()));
            
            return response()->json([
                'success' => false,
                'message' => 'Error database: ' . $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating penagihan: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $penagihan = Penagihan::findOrFail($id);
            $jumlah_siswa = 0;
            $siswa_list = collect();

            if ($penagihan->target === 'individu' && $penagihan->target_siswa) {
                $siswa_ids = $this->parseTargetSiswa($penagihan->target_siswa);
                $siswa_list = User::whereIn('id', $siswa_ids)
                    ->select('id', 'name as nama', 'nis', 'kelas', 'jurusan')
                    ->get();
                $jumlah_siswa = $siswa_list->count();
            } else {
                $query = User::where('role', 'siswa');
                if ($penagihan->kelas) {
                    $query->where('kelas', $penagihan->kelas);
                }
                if ($penagihan->jurusan) {
                    $query->where('jurusan', $penagihan->jurusan);
                }
                $jumlah_siswa = $query->count();
                $siswa_list = $query->select('id', 'name as nama', 'nis', 'kelas', 'jurusan')->get();
            }

            return view('bendahara.penagihan.show', compact('penagihan', 'jumlah_siswa', 'siswa_list'));

        } catch (\Exception $e) {
            Log::error('Error showing penagihan: ' . $e->getMessage());
            return redirect()->route('bendahara.penagihan.index')
                ->with('error', 'Gagal memuat detail penagihan');
        }
    }

    public function edit($id)
    {
        try {
            $penagihan = Penagihan::findOrFail($id);
            
            $kelas = ['10', '11', '12'];
            $jurusan = ['IPA', 'IPS'];
            $siswa = User::where('role', 'siswa')->get(['id', 'name', 'nis', 'kelas', 'jurusan']);
            
            $target_siswa = $this->parseTargetSiswa($penagihan->target_siswa);

            return view('bendahara.penagihan.edit', compact('penagihan', 'kelas', 'jurusan', 'siswa', 'target_siswa'));

        } catch (\Exception $e) {
            Log::error('Error editing penagihan: ' . $e->getMessage());
            return redirect()->route('bendahara.penagihan.index')
                ->with('error', 'Gagal memuat data penagihan');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'nominal' => 'required|numeric|min:1000',
            'jenis' => 'required|in:bulanan,tahunan,bebas',
            'target' => 'required|in:massal,individu',
            'tenggat_waktu' => 'required|date',
            'status' => 'required|in:0,1',
        ]);

        try {
            DB::beginTransaction();

            $penagihan = Penagihan::findOrFail($id);

            $updateData = [
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi ?? null,
                'nominal' => $request->nominal,
                'jenis' => $request->jenis,
                'target' => $request->target,
                'tenggat_waktu' => $request->tenggat_waktu,
                'status' => (int)$request->status,
                'kelas' => null,
                'jurusan' => null,
                'target_siswa' => null,
            ];

            if ($request->target === 'massal') {
                $updateData['kelas'] = $request->kelas;
                $updateData['jurusan'] = $request->jurusan;
            } elseif ($request->target === 'individu') {
                $siswaArray = is_array($request->target_siswa) ? $request->target_siswa : [];
                $updateData['target_siswa'] = !empty($siswaArray) ? json_encode($siswaArray) : null;
            }

            $penagihan->update($updateData);

            DB::commit();

            return redirect()->route('bendahara.penagihan.show', $penagihan->id)
                ->with('success', 'Penagihan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating penagihan', ['message' => $e->getMessage()]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $penagihan = Penagihan::findOrFail($id);
            $penagihan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Penagihan berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting penagihan', ['message' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generatePenagihanBulanan(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Fitur generate penagihan bulanan akan segera tersedia'
        ]);
    }

    /**
     * Helper method untuk parse target_siswa dengan aman
     */
    private function parseTargetSiswa($targetSiswa)
    {
        if (is_null($targetSiswa)) {
            return [];
        }

        if (is_array($targetSiswa)) {
            return $targetSiswa;
        }

        if (is_string($targetSiswa)) {
            $parsed = json_decode($targetSiswa, true);
            return is_array($parsed) ? $parsed : [];
        }

        return [];
    }

    private function generatePembayaranFromPenagihan($penagihan)
    {
        try {
            $siswa_ids = [];

            if ($penagihan->target === 'individu' && $penagihan->target_siswa) {
                $siswa_ids = $this->parseTargetSiswa($penagihan->target_siswa);
            } else {
                $query = User::where('role', 'siswa');
                if ($penagihan->kelas) {
                    $query->where('kelas', $penagihan->kelas);
                }
                if ($penagihan->jurusan) {
                    $query->where('jurusan', $penagihan->jurusan);
                }
                $siswa_ids = $query->pluck('id')->toArray();
            }

            Log::info('Generating pembayaran for siswa', ['count' => count($siswa_ids)]);

            // Buat jenis pembayaran
            $jenisPembayaran = JenisPembayaran::firstOrCreate(
                [
                    'nama' => $penagihan->judul
                ],
                [
                    'nominal' => $penagihan->nominal,
                    'keterangan' => $penagihan->deskripsi,
                    'kategori' => 'Lainnya',
                    'status' => 1
                ]
            );

            Log::info('Jenis pembayaran created/found', ['id' => $jenisPembayaran->id]);

            // Generate pembayaran untuk setiap siswa
            $pembayaranCreated = 0;
            foreach ($siswa_ids as $siswa_id) {
                $pembayaran = Pembayaran::firstOrCreate(
                    [
                        'user_id' => $siswa_id,
                        'jenis_pembayaran_id' => $jenisPembayaran->id,
                    ],
                    [
                        'status' => 'pending',
                        'tenggat_waktu' => $penagihan->tenggat_waktu,
                        'created_by' => auth()->id() ?? null
                    ]
                );

                if ($pembayaran->wasRecentlyCreated) {
                    $pembayaranCreated++;
                }
            }

            Log::info('Pembayaran records created', ['count' => $pembayaranCreated]);

        } catch (\Exception $e) {
            Log::error('Error generating pembayaran', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}