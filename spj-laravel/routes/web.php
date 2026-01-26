<?php

use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KonsumsiController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\HonorariumController;
use App\Http\Controllers\KwitansiController;
use App\Http\Controllers\NarasumberController;
use App\Http\Controllers\SBMKonsumsiController;
use App\Http\Controllers\SBMHonorariumController;
use App\Http\Controllers\UnorController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard (protected)
Route::get('/', function () {
    $totalKegiatan = \App\Models\Kegiatan::count();
    $totalAnggaran = 0; // TODO: hitung dari kwitansi
    $totalKwitansi = 0; // TODO: \App\Models\KwitansiBelanja::count();
    // Paginate recent activities (5 per page)
    $recentActivities = \App\Models\Kegiatan::latest()->paginate(5);

    return view('dashboard', compact('totalKegiatan', 'totalAnggaran', 'totalKwitansi', 'recentActivities'));
})->middleware('auth')->name('home');

// Master Routes (Admin only)
Route::prefix('master')->middleware(['auth', 'admin'])->group(function () {
    // Unor CRUD
    Route::resource('unor', UnorController::class)->names([
        'index' => 'master.unor.index',
        'create' => 'master.unor.create',
        'store' => 'master.unor.store',
        'edit' => 'master.unor.edit',
        'update' => 'master.unor.update',
        'destroy' => 'master.unor.destroy',
    ]);

    // Unit Kerja CRUD
    Route::resource('unit-kerja', UnitKerjaController::class)->names([
        'index' => 'master.unit-kerja.index',
        'create' => 'master.unit-kerja.create',
        'store' => 'master.unit-kerja.store',
        'edit' => 'master.unit-kerja.edit',
        'update' => 'master.unit-kerja.update',
        'destroy' => 'master.unit-kerja.destroy',
    ]);

    // SBM Konsumsi CRUD
    Route::resource('sbm-konsumsi', SBMKonsumsiController::class)->names([
        'index' => 'master.sbm-konsumsi.index',
        'create' => 'master.sbm-konsumsi.create',
        'store' => 'master.sbm-konsumsi.store',
        'edit' => 'master.sbm-konsumsi.edit',
        'update' => 'master.sbm-konsumsi.update',
        'destroy' => 'master.sbm-konsumsi.destroy',
    ]);

    // SBM Honorarium Management
    Route::get('/sbm-honorarium', [SBMHonorariumController::class, 'index'])
        ->name('master.sbm-honorarium');
    Route::put('/sbm-honorarium/{id}', [SBMHonorariumController::class, 'update'])
        ->name('master.sbm-honorarium.update');

    Route::resource('/waktu-konsumsi', App\Http\Controllers\Master\WaktuKonsumsiController::class)
        ->names('master.waktu-konsumsi');

    // MAK CRUD
    Route::resource('/mak', App\Http\Controllers\Master\MAKController::class)
        ->names('master.mak');

    // PPK CRUD
    Route::resource('/ppk', App\Http\Controllers\Master\PPKController::class)
        ->names('master.ppk');

    // Bendahara CRUD
    Route::resource('/bendahara', App\Http\Controllers\BendaharaController::class)
        ->names('master.bendahara');
});

// User Management Routes (Admin + Super Admin)
Route::middleware(['auth', 'admin'])->prefix('users')->group(function () {
    Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('/', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/{id}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

    // Super Admin only actions
    Route::post('/{id}/suspend', [App\Http\Controllers\UserController::class, 'suspend'])
        ->name('users.suspend')
        ->middleware('super_admin');
    Route::post('/{id}/activate', [App\Http\Controllers\UserController::class, 'activate'])
        ->name('users.activate')
        ->middleware('super_admin');
    Route::post('/{id}/reset-password', [App\Http\Controllers\UserController::class, 'resetPassword'])
        ->name('users.reset-password')
        ->middleware('super_admin');
});

// Kegiatan Routes
Route::resource('daftar-kegiatan', KegiatanController::class)->names([
    'index' => 'kegiatan.index',
    'create' => 'kegiatan.create',
    'store' => 'kegiatan.store',
    'show' => 'kegiatan.show',
    'edit' => 'kegiatan.edit',
    'update' => 'kegiatan.update',
    'destroy' => 'kegiatan.destroy',
]);
Route::get('daftar-kegiatan/{id}/pilih-detail', [KegiatanController::class, 'pilihDetail'])
    ->name('kegiatan.pilih-detail');

// Konsumsi Routes
Route::get('kegiatan/{id}/konsumsi/create', [KonsumsiController::class, 'create'])
    ->name('konsumsi.create');
Route::post('konsumsi', [KonsumsiController::class, 'store'])
    ->name('konsumsi.store');
Route::get('konsumsi/{id}/validasi', [KonsumsiController::class, 'validasiSBM'])
    ->name('konsumsi.validasi');
Route::get('konsumsi/{id}/koreksi', [KonsumsiController::class, 'koreksi'])
    ->name('konsumsi.koreksi');
Route::put('konsumsi/{id}/koreksi', [KonsumsiController::class, 'updateKoreksi'])
    ->name('konsumsi.update-koreksi');

// Narasumber Routes
Route::get('kegiatan/{id}/narasumber/create', [NarasumberController::class, 'create'])
    ->name('narasumber.create');
Route::post('narasumber', [NarasumberController::class, 'store'])
    ->name('narasumber.store');
Route::delete('narasumber/{id}', [NarasumberController::class, 'destroy'])
    ->name('narasumber.destroy');

// Narasumber Print Routes
Route::get('kegiatan/{id}/daftar-hadir-narasumber', [NarasumberController::class, 'daftarHadir'])
    ->name('narasumber.daftar-hadir');
Route::get('kegiatan/{id}/daftar-honorarium', [NarasumberController::class, 'daftarHonorarium'])
    ->name('narasumber.daftar-honorarium');

// Kwitansi Routes
Route::get('kwitansi/generate', [App\Http\Controllers\KwitansiController::class, 'generate'])
    ->name('kwitansi.generate');
Route::get('kwitansi/download/{kegiatan_id}/{jenis}', [App\Http\Controllers\KwitansiController::class, 'download'])
    ->name('kwitansi.download');
Route::get('kegiatan/{id}/daftar-hadir', [App\Http\Controllers\KwitansiController::class, 'daftarHadir'])
    ->name('kegiatan.daftar-hadir');

// Barang Routes
Route::get('kegiatan/{id}/barang/create', [BarangController::class, 'create'])
    ->name('barang.create');
Route::post('barang', [BarangController::class, 'store'])
    ->name('barang.store');

// Honorarium Routes (Belanja Jasa Profesi)
Route::get('kegiatan/{id}/honorarium/create', [HonorariumController::class, 'create'])
    ->name('honorarium.create');
Route::post('honorarium', [HonorariumController::class, 'store'])
    ->name('honorarium.store');

// Kwitansi Routes
Route::get('kwitansi/generate/{kegiatan_id}/{jenis}', [KwitansiController::class, 'generate'])
    ->name('kwitansi.generate');
Route::get('kwitansi/{id}/preview', [KwitansiController::class, 'preview'])
    ->name('kwitansi.preview');
Route::post('kwitansi/{id}/approve', [KwitansiController::class, 'approve'])
    ->name('kwitansi.approve');
Route::get('kwitansi/{id}/download', [KwitansiController::class, 'downloadPDF'])
    ->name('kwitansi.download');

