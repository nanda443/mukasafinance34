<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::whereIn('role', ['admin', 'bendahara', 'siswa'])
                ->when($request->global_search, function($query) use ($request) {
                    return $query->where(function($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->global_search . '%')
                          ->orWhere('email', 'like', '%' . $request->global_search . '%')
                          ->orWhere('nis', 'like', '%' . $request->global_search . '%')
                          ->orWhere('role', 'like', '%' . $request->global_search . '%');
                    });
                })
                ->when($request->role, function($query) use ($request) {
                    return $query->where('role', $request->role);
                })
                ->select(['id', 'name', 'email', 'nis', 'kelas', 'jurusan', 'role', 'created_at']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role_badge', function($row) {
                    $badgeClass = [
                        'admin' => 'danger',
                        'bendahara' => 'warning', 
                        'siswa' => 'success'
                    ][$row->role] ?? 'secondary';
                    
                    return '<span class="badge badge-' . $badgeClass . ' role-badge">' . strtoupper($row->role) . '</span>';
                })
                ->addColumn('action', function($row) {
                    return '
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-warning edit" data-id="'.$row->id.'" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger delete" data-id="'.$row->id.'" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['role_badge', 'action'])
                ->make(true);
        }

        return view('admin.user-management');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nis' => 'required|string|max:20|unique:users',
            'role' => 'required|in:admin,bendahara,siswa',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'nis' => $request->nis,
                'role' => $request->role,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ];

            // Jika role siswa, tambahkan kelas dan jurusan
            if ($request->role === 'siswa') {
                $userData['kelas'] = $request->kelas;
                $userData['jurusan'] = $request->jurusan;
            }

            User::create($userData);

            DB::commit();

            return response()->json([
                'success' => 'User berhasil dibuat!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            
            return response()->json([
                'errors' => ['general' => 'Terjadi kesalahan saat membuat user.']
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            
            return response()->json($user);
        } catch (\Exception $e) {
            Log::error('Error editing user: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'User tidak ditemukan.'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'nis' => 'required|string|max:20|unique:users,nis,' . $id,
            'role' => 'required|in:admin,bendahara,siswa',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'nis' => $request->nis,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Jika role siswa, update kelas dan jurusan
            if ($request->role === 'siswa') {
                $updateData['kelas'] = $request->kelas;
                $updateData['jurusan'] = $request->jurusan;
            } else {
                $updateData['kelas'] = null;
                $updateData['jurusan'] = null;
            }

            $user->update($updateData);

            DB::commit();

            return response()->json([
                'success' => 'User berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user: ' . $e->getMessage());
            
            return response()->json([
                'errors' => ['general' => 'Terjadi kesalahan saat memperbarui user.']
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Cek jika user sedang login
            if ($user->id === auth()->id()) {
                return response()->json([
                    'error' => 'Tidak dapat menghapus user yang sedang login.'
                ], 422);
            }

            $user->delete();

            return response()->json([
                'success' => 'User berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus user.'
            ], 500);
        }
    }
}