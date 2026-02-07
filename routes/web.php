<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BendaharaController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\PenagihanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/debug-storage', function () {
    try {
        // 1. Check Filesystem
        $path = config('filesystems.disks.public.root'); // Should be public_path('storage')
        $testFile = $path . '/test.txt';
        
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        file_put_contents($testFile, 'Hello World');
        $writeStatus = file_exists($testFile) ? 'YES' : 'NO';

        // 2. Check Database
        $logo = \App\Models\Setting::getValue('system_logo', 'NOT SET');
        $favicon = \App\Models\Setting::getValue('favicon', 'NOT SET');
        $bg = \App\Models\Setting::getValue('login_bg_image', 'NOT SET');

        return "
            <h1>Debug Info</h1>
            <p><strong>Storage Path (Config):</strong> $path</p>
            <p><strong>Write Permission Test:</strong> $writeStatus</p>
            <hr>
            <h3>Database Values:</h3>
            <ul>
                <li><strong>System Logo:</strong> $logo</li>
                <li><strong>Favicon:</strong> $favicon</li>
                <li><strong>Login BG:</strong> $bg</li>
            </ul>
        ";
    } catch (\Throwable $e) {
        return "Error: " . $e->getMessage() . "<br>Trace: " . $e->getTraceAsString();
    }
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
})->name('login');

// Custom Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Home redirect berdasarkan role
    Route::get('/home', function () {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'bendahara') {
            return redirect()->route('bendahara.dashboard');
        } elseif ($user->role === 'siswa') {
            return redirect()->route('siswa.dashboard');
        }

        return redirect('/');
    })->name('home');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Data Siswa
        Route::prefix('data-siswa')->name('data-siswa.')->group(function () {
            Route::post('/import', [AdminController::class, 'importSiswa'])->name('import');
            Route::get('/template', [AdminController::class, 'downloadTemplateSiswa'])->name('template');
            Route::get('/', [AdminController::class, 'dataSiswa'])->name('index');
            Route::post('/', [AdminController::class, 'storeSiswa'])->name('store');
            Route::get('/{id}', [AdminController::class, 'showSiswa'])->name('show');
            Route::get('/{id}/edit', [AdminController::class, 'editSiswa'])->name('edit');
            Route::put('/{id}', [AdminController::class, 'updateSiswa'])->name('update');
            Route::delete('/{id}', [AdminController::class, 'destroySiswa'])->name('destroy');
        });

        // User Management
        Route::prefix('user-management')->name('user-management.')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('index');
            Route::get('/{id}', [UserManagementController::class, 'show'])->name('show');
            Route::post('/', [UserManagementController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [UserManagementController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserManagementController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserManagementController::class, 'destroy'])->name('destroy');
        });

        // Jenis Pembayaran - ROUTES LENGKAP
        Route::prefix('jenis-pembayaran')->name('jenis-pembayaran.')->group(function () {
            Route::get('/', [AdminController::class, 'jenisPembayaran'])->name('index');
            Route::post('/', [AdminController::class, 'storeJenisPembayaran'])->name('store');
            Route::get('/{id}', [AdminController::class, 'showJenisPembayaran'])->name('show');
            Route::get('/{id}/edit', [AdminController::class, 'editJenisPembayaran'])->name('edit');
            Route::put('/{id}', [AdminController::class, 'updateJenisPembayaran'])->name('update');
            Route::delete('/{id}', [AdminController::class, 'destroyJenisPembayaran'])->name('destroy');
        });

        // Laporan
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/{id}', [LaporanController::class, 'show'])->name('show');
            Route::get('/export/pdf', [LaporanController::class, 'exportPDF'])->name('pdf');
            Route::get('/export/excel', [LaporanController::class, 'exportExcel'])->name('excel');
        });

        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [\App\Http\Controllers\SettingController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\SettingController::class, 'update'])->name('update');
            Route::post('/clear-cache', [\App\Http\Controllers\SettingController::class, 'clearCache'])->name('clear-cache');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Bendahara Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('bendahara')->name('bendahara.')->middleware('role:bendahara')->group(function () {

        // Dashboard
        Route::get('/dashboard', [BendaharaController::class, 'dashboard'])->name('dashboard');

        // Approval Pembayaran
        Route::prefix('approval-pembayaran')->name('approval-pembayaran.')->group(function () {
            Route::get('/', [BendaharaController::class, 'approvalPembayaran'])->name('index');
            Route::get('/{id}', [BendaharaController::class, 'showPembayaran'])->name('show');
            Route::post('/{id}/approve', [BendaharaController::class, 'approvePembayaran'])->name('approve');
            Route::post('/{id}/reject', [BendaharaController::class, 'rejectPembayaran'])->name('reject');
        });

        // Data Pembayaran
        Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
            Route::get('/{id}', [BendaharaController::class, 'showPembayaran'])->name('show');
        });

        // Data Siswa
        Route::prefix('data-siswa')->name('data-siswa.')->group(function () {
            Route::get('/', [BendaharaController::class, 'dataSiswa'])->name('index');
            Route::post('/', [BendaharaController::class, 'storeSiswa'])->name('store');
            Route::get('/{id}', [BendaharaController::class, 'showSiswa'])->name('show');
            Route::get('/{id}/edit', [BendaharaController::class, 'editSiswa'])->name('edit');
            Route::put('/{id}', [BendaharaController::class, 'updateSiswa'])->name('update');
            Route::delete('/{id}', [BendaharaController::class, 'destroySiswa'])->name('destroy');
        });

        // Jenis Pembayaran
        Route::prefix('jenis-pembayaran')->name('jenis-pembayaran.')->group(function () {
            Route::get('/', [BendaharaController::class, 'jenisPembayaran'])->name('index');
            Route::post('/', [BendaharaController::class, 'storeJenisPembayaran'])->name('store');
            Route::get('/{id}', [BendaharaController::class, 'showJenisPembayaran'])->name('show');
            Route::get('/{id}/edit', [BendaharaController::class, 'editJenisPembayaran'])->name('edit');
            Route::put('/{id}', [BendaharaController::class, 'updateJenisPembayaran'])->name('update');
            Route::delete('/{id}', [BendaharaController::class, 'destroyJenisPembayaran'])->name('destroy');
        });

        // Laporan
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/{id}', [LaporanController::class, 'show'])->name('show');
            Route::get('/export/pdf', [LaporanController::class, 'exportPDF'])->name('pdf');
            Route::get('/export/excel', [LaporanController::class, 'exportExcel'])->name('excel');
        });

        // Laporan Keuangan
        Route::prefix('laporan-keuangan')->name('laporan-keuangan.')->group(function () {
            Route::get('/', [BendaharaController::class, 'laporanKeuangan'])->name('index');
            Route::get('/export/pdf', [BendaharaController::class, 'exportLaporanKeuanganPDF'])->name('pdf');
            Route::get('/export/excel', [BendaharaController::class, 'exportLaporanKeuanganExcel'])->name('excel');
        });

        // Penagihan
        Route::prefix('penagihan')->name('penagihan.')->group(function () {
            Route::get('/', [PenagihanController::class, 'index'])->name('index');
            Route::post('/', [PenagihanController::class, 'store'])->name('store');
            Route::get('/{id}', [PenagihanController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PenagihanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PenagihanController::class, 'update'])->name('update');
            Route::delete('/{id}', [PenagihanController::class, 'destroy'])->name('destroy');
            Route::post('/generate-bulanan', [PenagihanController::class, 'generatePenagihanBulanan'])->name('generate-bulanan');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Siswa Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('siswa')->name('siswa.')->middleware('role:siswa')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');

        // Tagihan
        Route::prefix('tagihan')->name('tagihan.')->group(function () {
            Route::get('/', [SiswaController::class, 'tagihan'])->name('index');
            Route::get('/{id}', [SiswaController::class, 'showTagihan'])->name('show');
            Route::post('/bayar', [SiswaController::class, 'bayar'])->name('bayar');
        });

        // Profile
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [SiswaController::class, 'profile'])->name('index');
            Route::put('/', [SiswaController::class, 'updateProfile'])->name('update');
        });

        // Riwayat Pembayaran
        Route::prefix('riwayat')->name('riwayat.')->group(function () {
            Route::get('/', [SiswaController::class, 'riwayatPembayaran'])->name('index');
            Route::get('/{id}', [SiswaController::class, 'showRiwayat'])->name('show');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/