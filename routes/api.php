<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Sanctum\LoginController;
use App\Http\Controllers\App\Siswa\PresensiController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Storage;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth');


Route::post('/login', [LoginController::class, 'postLogin'])->name('sanctum.login.post');
Route::post('/logout', [LoginController::class, 'postLogin'])->name('sanctum.logout.post');


Route::get('/images/{filename}', [ImageController::class, 'getImgFile'])
->middleware('signed')
->name('image.file.show');


Route::group(['middleware'=>['auth:sanctum','role:siswa']], function() {
    Route::post('/siswa/presensi', [PresensiController::class, 'store']);
    Route::get('/siswa/presensi', [PresensiController::class, 'create']);
});

