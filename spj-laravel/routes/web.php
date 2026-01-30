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
use App\Http\Controllers\ActivityLogController;
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
// Calendar API endpoint
Route::get('/api/calendar-events', function () {
    $user = auth()->user();

    $query = \App\Models\Kegiatan::select('id', 'nama_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'unit_kerja_id');

    // Filter by user's unit kerja if not super admin
    if (!$user->hasRole('super-admin') && $user->id_unker) {
        $query->where('unit_kerja_id', $user->id_unker);
    }

    // Color palette for events
    $colors = [
        ['bg' => '#3b82f6', 'border' => '#2563eb'], // blue
        ['bg' => '#10b981', 'border' => '#059669'], // green
        ['bg' => '#f59e0b', 'border' => '#d97706'], // amber
        ['bg' => '#8b5cf6', 'border' => '#7c3aed'], // violet
        ['bg' => '#ef4444', 'border' => '#dc2626'], // red
        ['bg' => '#06b6d4', 'border' => '#0891b2'], // cyan
        ['bg' => '#ec4899', 'border' => '#db2777'], // pink
        ['bg' => '#84cc16', 'border' => '#65a30d'], // lime
        ['bg' => '#f97316', 'border' => '#ea580c'], // orange
        ['bg' => '#6366f1', 'border' => '#4f46e5'], // indigo
    ];

    $kegiatan = $query->get()->map(function ($k, $index) use ($colors) {
        $colorIndex = $index % count($colors);
        return [
            'id' => $k->id,
            'title' => $k->nama_kegiatan,
            'start' => $k->tanggal_mulai,
            'end' => $k->tanggal_selesai,
            'backgroundColor' => $colors[$colorIndex]['bg'],
            'borderColor' => $colors[$colorIndex]['border'],
            'textColor' => '#ffffff',
        ];
    });

    return response()->json($kegiatan);
})->middleware('auth')->name('api.calendar-events');

// EHRM API - Fetch Pegawai by NIP
Route::get('/api/ehrm/pegawai/{nip}', function ($nip) {
    try {
        \Log::info('EHRM API called with NIP: ' . $nip);

        $ehrmService = new \App\Services\EHRMService();
        $pegawai = $ehrmService->getPegawaiByNip($nip);

        \Log::info('EHRM API result: ', ['pegawai' => $pegawai]);

        if ($pegawai) {
            return response()->json([
                'success' => true,
                'data' => $pegawai
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pegawai tidak ditemukan'
        ], 404);
    } catch (\Exception $e) {
        \Log::error('EHRM API error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
})->middleware('auth')->name('api.ehrm.pegawai');

// API to get MAK by PPK NIP
Route::get('/api/mak-by-ppk/{nip}', function ($nip) {
    $maks = \App\Models\MAK::where('nip_ppk', $nip)
        ->select('id', 'kode', 'nama', 'tahun')
        ->orderBy('nama')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $maks
    ]);
})->middleware('auth')->name('api.mak.by-ppk');

// Master Routes - Unor
Route::prefix('master')->middleware(['auth'])->group(function () {
    Route::get('unor', [UnorController::class, 'index'])
        ->middleware('permission:view-unor')
        ->name('master.unor.index');
    Route::get('unor/create', [UnorController::class, 'create'])
        ->middleware('permission:create-unor')
        ->name('master.unor.create');
    Route::post('unor', [UnorController::class, 'store'])
        ->middleware('permission:create-unor')
        ->name('master.unor.store');
    Route::get('unor/{unor}/edit', [UnorController::class, 'edit'])
        ->middleware('permission:edit-unor')
        ->name('master.unor.edit');
    Route::put('unor/{unor}', [UnorController::class, 'update'])
        ->middleware('permission:edit-unor')
        ->name('master.unor.update');
    Route::delete('unor/{unor}', [UnorController::class, 'destroy'])
        ->middleware('permission:delete-unor')
        ->name('master.unor.destroy');

    // Unit Kerja CRUD
    Route::get('unit-kerja', [UnitKerjaController::class, 'index'])
        ->middleware('permission:view-unit-kerja')
        ->name('master.unit-kerja.index');
    Route::get('unit-kerja/create', [UnitKerjaController::class, 'create'])
        ->middleware('permission:create-unit-kerja')
        ->name('master.unit-kerja.create');
    Route::post('unit-kerja', [UnitKerjaController::class, 'store'])
        ->middleware('permission:create-unit-kerja')
        ->name('master.unit-kerja.store');
    Route::get('unit-kerja/{unit_kerja}/edit', [UnitKerjaController::class, 'edit'])
        ->middleware('permission:edit-unit-kerja')
        ->name('master.unit-kerja.edit');
    Route::put('unit-kerja/{unit_kerja}', [UnitKerjaController::class, 'update'])
        ->middleware('permission:edit-unit-kerja')
        ->name('master.unit-kerja.update');
    Route::delete('unit-kerja/{unit_kerja}', [UnitKerjaController::class, 'destroy'])
        ->middleware('permission:delete-unit-kerja')
        ->name('master.unit-kerja.destroy');

    // SBM Konsumsi CRUD
    Route::get('sbm-konsumsi', [SBMKonsumsiController::class, 'index'])
        ->middleware('permission:view-sbm-konsumsi')
        ->name('master.sbm-konsumsi.index');
    Route::get('sbm-konsumsi/create', [SBMKonsumsiController::class, 'create'])
        ->middleware('permission:create-sbm-konsumsi')
        ->name('master.sbm-konsumsi.create');
    Route::post('sbm-konsumsi', [SBMKonsumsiController::class, 'store'])
        ->middleware('permission:create-sbm-konsumsi')
        ->name('master.sbm-konsumsi.store');
    Route::get('sbm-konsumsi/{sbm_konsumsi}/edit', [SBMKonsumsiController::class, 'edit'])
        ->middleware('permission:edit-sbm-konsumsi')
        ->name('master.sbm-konsumsi.edit');
    Route::put('sbm-konsumsi/{sbm_konsumsi}', [SBMKonsumsiController::class, 'update'])
        ->middleware('permission:edit-sbm-konsumsi')
        ->name('master.sbm-konsumsi.update');
    Route::delete('sbm-konsumsi/{sbm_konsumsi}', [SBMKonsumsiController::class, 'destroy'])
        ->middleware('permission:delete-sbm-konsumsi')
        ->name('master.sbm-konsumsi.destroy');

    // SBM Honorarium CRUD
    Route::get('sbm-honorarium', [SBMHonorariumController::class, 'index'])
        ->middleware('permission:view-sbm-honorarium')
        ->name('master.sbm-honorarium.index');
    Route::get('sbm-honorarium/create', [SBMHonorariumController::class, 'create'])
        ->middleware('permission:create-sbm-honorarium')
        ->name('master.sbm-honorarium.create');
    Route::post('sbm-honorarium', [SBMHonorariumController::class, 'store'])
        ->middleware('permission:create-sbm-honorarium')
        ->name('master.sbm-honorarium.store');
    Route::get('sbm-honorarium/{sbm_honorarium}/edit', [SBMHonorariumController::class, 'edit'])
        ->middleware('permission:edit-sbm-honorarium')
        ->name('master.sbm-honorarium.edit');
    Route::put('sbm-honorarium/{sbm_honorarium}', [SBMHonorariumController::class, 'update'])
        ->middleware('permission:edit-sbm-honorarium')
        ->name('master.sbm-honorarium.update');
    Route::delete('sbm-honorarium/{sbm_honorarium}', [SBMHonorariumController::class, 'destroy'])
        ->middleware('permission:delete-sbm-honorarium')
        ->name('master.sbm-honorarium.destroy');

    // Waktu Konsumsi CRUD
    Route::get('waktu-konsumsi', [App\Http\Controllers\Master\WaktuKonsumsiController::class, 'index'])
        ->middleware('permission:view-waktu-konsumsi')
        ->name('master.waktu-konsumsi.index');
    Route::get('waktu-konsumsi/create', [App\Http\Controllers\Master\WaktuKonsumsiController::class, 'create'])
        ->middleware('permission:create-waktu-konsumsi')
        ->name('master.waktu-konsumsi.create');
    Route::post('waktu-konsumsi', [App\Http\Controllers\Master\WaktuKonsumsiController::class, 'store'])
        ->middleware('permission:create-waktu-konsumsi')
        ->name('master.waktu-konsumsi.store');
    Route::get('waktu-konsumsi/{waktu_konsumsi}/edit', [App\Http\Controllers\Master\WaktuKonsumsiController::class, 'edit'])
        ->middleware('permission:edit-waktu-konsumsi')
        ->name('master.waktu-konsumsi.edit');
    Route::put('waktu-konsumsi/{waktu_konsumsi}', [App\Http\Controllers\Master\WaktuKonsumsiController::class, 'update'])
        ->middleware('permission:edit-waktu-konsumsi')
        ->name('master.waktu-konsumsi.update');
    Route::delete('waktu-konsumsi/{waktu_konsumsi}', [App\Http\Controllers\Master\WaktuKonsumsiController::class, 'destroy'])
        ->middleware('permission:delete-waktu-konsumsi')
        ->name('master.waktu-konsumsi.destroy');

    // MAK CRUD
    Route::get('mak', [App\Http\Controllers\Master\MAKController::class, 'index'])
        ->middleware('permission:view-mak')
        ->name('master.mak.index');
    Route::get('mak/create', [App\Http\Controllers\Master\MAKController::class, 'create'])
        ->middleware('permission:create-mak')
        ->name('master.mak.create');
    Route::post('mak', [App\Http\Controllers\Master\MAKController::class, 'store'])
        ->middleware('permission:create-mak')
        ->name('master.mak.store');
    Route::get('mak/{mak}/edit', [App\Http\Controllers\Master\MAKController::class, 'edit'])
        ->middleware('permission:edit-mak')
        ->name('master.mak.edit');
    Route::put('mak/{mak}', [App\Http\Controllers\Master\MAKController::class, 'update'])
        ->middleware('permission:edit-mak')
        ->name('master.mak.update');
    Route::delete('mak/{mak}', [App\Http\Controllers\Master\MAKController::class, 'destroy'])
        ->middleware('permission:delete-mak')
        ->name('master.mak.destroy');
    Route::get('mak/sync', [App\Http\Controllers\Master\MAKController::class, 'sync'])
        ->middleware('permission:create-mak')
        ->name('master.mak.sync');

    // PPK CRUD
    Route::get('ppk', [App\Http\Controllers\Master\PPKController::class, 'index'])
        ->middleware('permission:view-ppk')
        ->name('master.ppk.index');
    Route::get('ppk/create', [App\Http\Controllers\Master\PPKController::class, 'create'])
        ->middleware('permission:create-ppk')
        ->name('master.ppk.create');
    Route::post('ppk', [App\Http\Controllers\Master\PPKController::class, 'store'])
        ->middleware('permission:create-ppk')
        ->name('master.ppk.store');
    Route::get('ppk/{ppk}/edit', [App\Http\Controllers\Master\PPKController::class, 'edit'])
        ->middleware('permission:edit-ppk')
        ->name('master.ppk.edit');
    Route::put('ppk/{ppk}', [App\Http\Controllers\Master\PPKController::class, 'update'])
        ->middleware('permission:edit-ppk')
        ->name('master.ppk.update');
    Route::delete('ppk/{ppk}', [App\Http\Controllers\Master\PPKController::class, 'destroy'])
        ->middleware('permission:delete-ppk')
        ->name('master.ppk.destroy');
    Route::get('ppk/sync', [App\Http\Controllers\Master\PPKController::class, 'sync'])
        ->middleware('permission:create-ppk')
        ->name('master.ppk.sync');

    // Bendahara CRUD
    Route::get('bendahara', [App\Http\Controllers\BendaharaController::class, 'index'])
        ->middleware('permission:view-bendahara')
        ->name('master.bendahara.index');
    Route::get('bendahara/create', [App\Http\Controllers\BendaharaController::class, 'create'])
        ->middleware('permission:create-bendahara')
        ->name('master.bendahara.create');
    Route::post('bendahara', [App\Http\Controllers\BendaharaController::class, 'store'])
        ->middleware('permission:create-bendahara')
        ->name('master.bendahara.store');
    Route::get('bendahara/{bendahara}/edit', [App\Http\Controllers\BendaharaController::class, 'edit'])
        ->middleware('permission:edit-bendahara')
        ->name('master.bendahara.edit');
    Route::put('bendahara/{bendahara}', [App\Http\Controllers\BendaharaController::class, 'update'])
        ->middleware('permission:edit-bendahara')
        ->name('master.bendahara.update');
    Route::delete('bendahara/{bendahara}', [App\Http\Controllers\BendaharaController::class, 'destroy'])
        ->middleware('permission:delete-bendahara')
        ->name('master.bendahara.destroy');
});

// User Management Routes
Route::middleware(['auth'])->prefix('users')->group(function () {
    Route::get('/', [App\Http\Controllers\UserController::class, 'index'])
        ->middleware('permission:view-users')
        ->name('users.index');
    Route::get('/create', [App\Http\Controllers\UserController::class, 'create'])
        ->middleware('permission:create-users')
        ->name('users.create');
    Route::post('/', [App\Http\Controllers\UserController::class, 'store'])
        ->middleware('permission:create-users')
        ->name('users.store');
    Route::get('/{id}/edit', [App\Http\Controllers\UserController::class, 'edit'])
        ->middleware('permission:edit-users')
        ->name('users.edit');
    Route::put('/{id}', [App\Http\Controllers\UserController::class, 'update'])
        ->middleware('permission:edit-users')
        ->name('users.update');
    Route::delete('/{id}', [App\Http\Controllers\UserController::class, 'destroy'])
        ->middleware('permission:delete-users')
        ->name('users.destroy');

    // Super Admin only actions
    Route::post('/{id}/suspend', [App\Http\Controllers\UserController::class, 'suspend'])
        ->name('users.suspend')
        ->middleware('role:super-admin');
    Route::post('/{id}/activate', [App\Http\Controllers\UserController::class, 'activate'])
        ->name('users.activate')
        ->middleware('role:super-admin');
    Route::post('/{id}/reset-password', [App\Http\Controllers\UserController::class, 'resetPassword'])
        ->name('users.reset-password')
        ->middleware('role:super-admin');
});

// Role Management Routes (Super Admin only)
Route::middleware(['auth', 'role:super-admin'])->prefix('roles')->group(function () {
    Route::get('/', [App\Http\Controllers\RoleController::class, 'index'])->name('roles.index');
    Route::get('/create', [App\Http\Controllers\RoleController::class, 'create'])->name('roles.create');
    Route::post('/', [App\Http\Controllers\RoleController::class, 'store'])->name('roles.store');
    Route::get('/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('roles.update');
    Route::delete('/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('roles.destroy');
});

// Permission Management Routes (Super Admin only)
Route::middleware(['auth', 'role:super-admin'])->prefix('permissions')->group(function () {
    Route::get('/', [App\Http\Controllers\PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/create', [App\Http\Controllers\PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/', [App\Http\Controllers\PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/{id}/edit', [App\Http\Controllers\PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/{id}', [App\Http\Controllers\PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/{id}', [App\Http\Controllers\PermissionController::class, 'destroy'])->name('permissions.destroy');
});

// Kegiatan Routes
Route::middleware(['auth'])->group(function () {
    Route::get('daftar-kegiatan', [KegiatanController::class, 'index'])
        ->middleware('permission:view-kegiatan')
        ->name('kegiatan.index');
    Route::get('daftar-kegiatan/create', [KegiatanController::class, 'create'])
        ->middleware('permission:create-kegiatan')
        ->name('kegiatan.create');
    Route::post('daftar-kegiatan', [KegiatanController::class, 'store'])
        ->middleware('permission:create-kegiatan')
        ->name('kegiatan.store');
    Route::get('daftar-kegiatan/{id}', [KegiatanController::class, 'show'])
        ->middleware('permission:view-kegiatan')
        ->name('kegiatan.show');
    Route::get('daftar-kegiatan/{id}/edit', [KegiatanController::class, 'edit'])
        ->middleware('permission:edit-kegiatan')
        ->name('kegiatan.edit');
    Route::put('daftar-kegiatan/{id}', [KegiatanController::class, 'update'])
        ->middleware('permission:edit-kegiatan')
        ->name('kegiatan.update');
    Route::delete('daftar-kegiatan/{id}', [KegiatanController::class, 'destroy'])
        ->middleware('permission:delete-kegiatan')
        ->name('kegiatan.destroy');
    Route::get('daftar-kegiatan/{id}/pilih-detail', [KegiatanController::class, 'pilihDetail'])
        ->middleware('permission:view-kegiatan')
        ->name('kegiatan.pilih-detail');
});

// Konsumsi Routes
Route::middleware(['auth'])->group(function () {
    Route::get('kegiatan/{id}/konsumsi/create', [KonsumsiController::class, 'create'])
        ->middleware('permission:create-konsumsi')
        ->name('konsumsi.create');
    Route::post('konsumsi', [KonsumsiController::class, 'store'])
        ->middleware('permission:create-konsumsi')
        ->name('konsumsi.store');
    Route::delete('konsumsi/{id}', [KonsumsiController::class, 'destroy'])
        ->middleware('permission:delete-konsumsi')
        ->name('konsumsi.destroy');
    Route::get('konsumsi/{id}/validasi', [KonsumsiController::class, 'validasiSBM'])
        ->middleware('permission:validasi-konsumsi')
        ->name('konsumsi.validasi');
    Route::get('konsumsi/{id}/koreksi', [KonsumsiController::class, 'koreksi'])
        ->middleware('permission:edit-konsumsi')
        ->name('konsumsi.koreksi');
    Route::put('konsumsi/{id}/koreksi', [KonsumsiController::class, 'updateKoreksi'])
        ->middleware('permission:edit-konsumsi')
        ->name('konsumsi.update-koreksi');
});

// Narasumber Routes
Route::middleware(['auth'])->group(function () {
    Route::get('kegiatan/{id}/narasumber/create', [NarasumberController::class, 'create'])
        ->middleware('permission:create-narasumber')
        ->name('narasumber.create');
    Route::post('narasumber', [NarasumberController::class, 'store'])
        ->middleware('permission:create-narasumber')
        ->name('narasumber.store');
    Route::delete('narasumber/{id}', [NarasumberController::class, 'destroy'])
        ->middleware('permission:delete-narasumber')
        ->name('narasumber.destroy');

    // Narasumber Print Routes
    Route::get('kegiatan/{id}/daftar-hadir-narasumber', [NarasumberController::class, 'daftarHadir'])
        ->middleware('permission:view-narasumber')
        ->name('narasumber.daftar-hadir');
    Route::get('kegiatan/{id}/daftar-honorarium', [NarasumberController::class, 'daftarHonorarium'])
        ->middleware('permission:view-narasumber')
        ->name('narasumber.daftar-honorarium');
});

// Kwitansi Routes
Route::middleware(['auth'])->group(function () {
    Route::get('kwitansi/generate', [App\Http\Controllers\KwitansiController::class, 'generate'])
        ->middleware('permission:create-kwitansi')
        ->name('kwitansi.generate');
    Route::get('kwitansi/download/{kegiatan_id}/{jenis}', [App\Http\Controllers\KwitansiController::class, 'download'])
        ->middleware('permission:download-kwitansi')
        ->name('kwitansi.download');
    Route::get('kegiatan/{id}/daftar-hadir', [App\Http\Controllers\KwitansiController::class, 'daftarHadir'])
        ->middleware('permission:view-kwitansi')
        ->name('kegiatan.daftar-hadir');
    Route::get('kwitansi/generate/{kegiatan_id}/{jenis}', [KwitansiController::class, 'generate'])
        ->middleware('permission:create-kwitansi')
        ->name('kwitansi.generate');
    Route::get('kwitansi/{id}/preview', [KwitansiController::class, 'preview'])
        ->middleware('permission:view-kwitansi')
        ->name('kwitansi.preview');
    Route::post('kwitansi/{id}/approve', [KwitansiController::class, 'approve'])
        ->middleware('permission:approve-kwitansi')
        ->name('kwitansi.approve');
    Route::get('kwitansi/{id}/download', [KwitansiController::class, 'downloadPDF'])
        ->middleware('permission:download-kwitansi')
        ->name('kwitansi.download');
});

// Activity Logs Routes
Route::prefix('activity-logs')->middleware(['auth'])->group(function () {
    Route::get('/', [ActivityLogController::class, 'index'])
        ->name('activity-logs.index');
    Route::get('/{id}', [ActivityLogController::class, 'show'])
        ->name('activity-logs.show');
});
