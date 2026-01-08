<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $claim;
        $nis=$claim->get("nis");
        $siswa=Siswa::find($nis);
        // refactor ke middleware
        if($siswa==null)
        {
            return response()->json([
                "message"=>"token tidak valid"
            ], 422);
        }

        $jadwals=\App\Models\Kelas::with("penugasans")->where("ruang_kelas", $siswa->id_ruang)->get();
        return response()->json([
            "message"=>"jadwal kelas untuk siswa $nis",
            "data"=>$jadwals
        ], 200);
    }
    public function show($id)
    {
        $claim;
        $nis=$claim->get("nis");
        $siswa=Siswa::find($nis);
        if($siswa==null)
        {
            return response()->json([
                "message"=>"token tidak valid"
            ], 422);
        }

        $jadwal=Jadwal::with("penugasans")->find($id);
        if($jadwal->id_ruang!=$siswa->id_ruang)
        {
            return response()->json([
                "message"=>"unauthorized request"
            ], 400);
        }
        return response()->json([
            "message"=>"jadwal kelas $jadwal->id untuk siswa $nis",
            "data"=>$jadwal
        ], 200);
    }
}
