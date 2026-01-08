<?php

namespace App\Http\Controllers\Dashboard\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function joinClass(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:6|exists:kelas,token',
        ]);

        $user = Auth::user();
        $guru = $user->profile; // Assumes User model has 'profile' accessor or relationship working

        if (!$guru) {
             return back()->with('error', 'Data guru tidak ditemukan.');
        }

        // Check if already has class (optional, but good safety)
        if ($guru->id_kelas) {
            return back()->with('error', 'Anda sudah terhubung ke kelas.');
        }

        $kelas = Kelas::where('token', $request->token)->first();

        if (!$kelas) {
            return back()->with('error', 'Token kelas tidak valid.');
        }

        $guru->update(['id_kelas' => $kelas->id]);

        return back()->with('success', 'Berhasil bergabung ke kelas ' . $kelas->nama);
    }
}
