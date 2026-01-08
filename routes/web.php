<?php

use App\Http\Controllers\Admin\DiaryController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\PresensiController as AdminPresensiController;
use App\Http\Controllers\Admin\PresensiLiburDataController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TahunAkademikController;
use App\Http\Controllers\App\Siswa\PresensiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\Admin\JadwalHarianController;
use App\Http\Controllers\Dashboard\Admin\PresensiLiburController as HariLiburController;
use App\Http\Controllers\Dashboard\Admin\PresensiLiburController;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Auth;



// 1. Root/Default Page
// Mengarahkan pengguna berdasarkan status login. Jika sudah login, ke dashboard, jika belum, ke login.
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        return redirect()->intended("/$role/dashboard");
    }
    return redirect()->route('login');
})->name('root');

// 2. Guest Routes (Hanya diakses jika BELUM login)
Route::group(['middleware'=>['guest']], function() {
    // Login
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'postLogin'])->name('web.login.post');
    // Forgot Password
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
});

// 3. Authenticated Routes (Hanya diakses jika SUDAH login)
Route::group(['middleware'=>['auth']], function() {
    Route::get('/files/{mime}/{type}/default', [ImageController::class, 'webDefault'])
    ->name('image.web.default');
    Route::get('/files/{mime}/{type}/{id_col}/{id}/{filepath}', [ImageController::class, 'webGet'])
    ->name('image.web.show');
    Route::post('/web/logout', [LoginController::class, 'postLogout'])->name('web.logout.post');
});
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    // Route::view('/siswa/mental', 'admin.siswa.mental.index')->name('siswa.mental.index');
    Route::get('/siswa/diary', [DiaryController::class, 'index'])->name('siswa.diary.index');
    Route::get('/siswa/kehadiran', [AdminPresensiController::class, 'index'])->name('siswa.kehadiran.index');
    Route::resource('/siswa', SiswaController::class);
    Route::resource('/guru', \App\Http\Controllers\Admin\GuruController::class)->except(['create', 'show', 'edit']);
    Route::resource('/kelas', KelasController::class)->except(['edit'])->parameter("kelas", "kelas");
    Route::resource('/tahun-akademik', TahunAkademikController::class)->names('tahun-akademik');
    Route::get('/siswa/kehadiran/{student}/{year}', [AdminPresensiController::class, 'show'])->name('siswa.kehadiran.show');
    Route::post('/presensi-libur', [PresensiLiburController::class, 'store'])->name('presensi-libur.store');
    Route::delete('/presensi-libur', [PresensiLiburController::class, 'destroy'])->name('presensi-libur.destroy');
    Route::post('/admin/jadwal-kehadiran', [JadwalHarianController::class, 'update'])->name('jadwal-harian.update');
    Route::post('/config/diary', [DiaryController::class, 'updateConfig'])->name('config.diary.update');
    // Route::get('/dashboard', [DashboardController::class, 'adminDashboard'] );
    // Route::view('/kelas', 'admin.kelas.index')->name('admin.kelas.index');
    // Route::view('/tahun-akademik', 'admin.tahun_akademik.index')->name('admin.thak.index');
    
    // Route::get('/hari-libur', [HariLiburController::class, 'index'])->name('admin.libur.index');
});

// 5. Guru Routes (Akses: Auth + Role Guru)
Route::group(['middleware'=>['auth', 'role:guru']], function() {
    Route::get('/guru/dashboard', [DashboardController::class, 'guruDashboard'] );
});
// Auth Routes
// Route::view('/login', 'auth.login')->name('login');
// Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

// Dashboard Siswa Routes
Route::view('/siswa/login', 'auth.sanctum_login')->name('siswa.login');
Route::view('/siswa/presensi', 'siswa.presensi')->name('siswa.presensi');
Route::view('/siswa/jadwal', 'siswa.jadwal')->name('siswa.jadwal');
// Route::view('/siswa', 'dashboard.siswa')->name('siswa.dashboard');

// /* 1. Root/Default Page */
// // Mengarahkan pengguna berdasarkan status login. Jika sudah login, ke dashboard, jika belum, ke login.
// Route::get('/', function () {
//     if (Auth::check()) {
//         $role = Auth::user()->role;
//         return redirect()->intended("/$role/dashboard");
//     }
//     return redirect()->route('login');
// })->name('root');

// // 2. Guest Routes (Hanya diakses jika BELUM login)
// Route::group(['middleware'=>['guest']], function() {
//     // Login
//     Route::get('/login', [LoginController::class, 'index'])->name('login');
//     Route::post('/login', [LoginController::class, 'postLogin'])->name('web.login.post');

//     // Forgot Password
//     Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
// });

// // 3. Authenticated Routes (Hanya diakses jika SUDAH login)
Route::group(['middleware'=>['auth']], function() {
    // Logout (Menggunakan nama 'logout' yang lebih standar)
    Route::post('/logout', [LoginController::class, 'postLogout'])->name('logout');
});

// 4. Admin Routes (Akses: Auth + Role Admin)
Route::group(['middleware'=>['auth', 'role:admin']], function() {
    // Dashboard Admin (Ditambahkan nama route)
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

    // Route::view('/admin/kelas', 'admin.kelas.index')->name('admin.kelas.index');
    // Route::view('/admin/tahun-akademik', 'admin.tahun_akademik.index')->name('admin.ta.index');

    // // Resource Controller (Siswa dan Hari Libur)
    // Route::resource('/admin/siswa', AdminSiswaController::class, ['as' => 'admin']);
    Route::resource('/admin/hari-libur', HariLiburController::class, ['as' => 'admin']);
});

// 5. Guru Routes (Akses: Auth + Role Guru)
Route::group(['middleware'=>['auth', 'role:guru']], function() {
    // Dashboard Guru (Ditambahkan nama route)
    Route::get('/guru/dashboard', [DashboardController::class, 'guruDashboard'])->name('guru.dashboard');
    
    // Fitur Guru Lainnya
    Route::get('/guru/laporan-mood', [\App\Http\Controllers\Dashboard\Guru\GuruSiswaController::class, 'moodIndex'])->name('guru.mood.index');
    Route::get('/guru/laporan-mood/{siswa}', [\App\Http\Controllers\Dashboard\Guru\GuruSiswaController::class, 'moodDetail'])->name('guru.mood.detail');
    Route::get('/guru/laporan-mood/{siswa}/export', [\App\Http\Controllers\Dashboard\Guru\GuruSiswaController::class, 'exportMoodCsv'])->name('guru.mood.export');
    Route::get('/guru/laporan-nilai', [\App\Http\Controllers\Dashboard\Guru\GuruSiswaController::class, 'nilaiIndex'])->name('guru.nilai.index');
    
    // Class Management
    Route::post('/guru/class/join', [\App\Http\Controllers\Dashboard\Guru\ClassController::class, 'joinClass'])->name('guru.class.join');
});

// 6. Siswa Routes (Akses: Auth + Role Siswa)
Route::group(['middleware'=>['auth', 'role:siswa']], function() {
    // Dashboard Siswa
    
    // Route Siswa Lainnya
    Route::group(['middleware'=>['survey_check:0']], function() {
        Route::get('/siswa/dashboard', [DashboardController::class, 'siswaDashboard'])->name('siswa.dashboard');

        Route::view('/siswa/presensi', 'siswa.presensi')->name('siswa.presensi');
        Route::post('/siswa/presensi', [PresensiController::class, 'store'])->name('siswa.presensi.store');
        Route::get('/siswa/statistik', [App\Http\Controllers\App\Siswa\StatistikController::class, 'index'])->name('siswa.statistik');
    });
    
    Route::group(['middleware'=>['survey_check:1']], function() {
        Route::view('/form-input-dass21', 'dass21.form-input')->name('dass21.form');
        Route::post('/siswa/dass21', [App\Http\Controllers\App\Siswa\Dass21Controller::class, 'store'])->name('dass21.store');
    });

    Route::get('/siswa/diaryku', [App\Http\Controllers\App\Siswa\Dass21Controller::class, 'diarykuDashboard'])->name('siswa.diaryku');
    // Route::view('/siswa/jadwal', 'siswa.jadwal')->name('siswa.jadwal');
    // Route::view('/siswa/laporan-nilai', 'siswa.laporan-nilai')->name('siswa.laporan-nilai');
});



