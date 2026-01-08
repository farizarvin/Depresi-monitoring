<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JadwalHarianController extends Controller
{
    //
    private $storage_path;
    public function __construct()
    {
        $filename='konfigurasi_jadwal_harian.json';
        $this->storage_path="/data/config/$filename";
    }
    public function index()
    {
        try
        {
            $config=Storage::json($this->storage_path, 'public');
            return;
        }
        catch(\Exception $e)
        {
            
        }
    }
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'hari_libur'=>'required|array',
            'hari_libur.*'=>'required|integer|between:0,6|distinct',
            'jadwal'=>'required|array|max:7',
            'jadwal.*'=>'required|array|between:3,3',
            'jadwal.*.0'=>'required|integer|between:0,6|distinct',
            'jadwal.*.1'=>'required|date_format:H:i',
            'jadwal.*.2'=>'required|date_format:H:i|after:jadwal.*.1'
        ]);
        if($validator->fails())
        {
            return;
        }

        try
        {
            $data=$validator->validated();
            $ctn_json=json_encode($data);
            Storage::put($this->storage_path, $ctn_json);
            return;
        }
        catch(\Exception $e)
        {
            return;
        }

    }
    public function update(Request $request)
    {
        // dd($request->all());
        $validator=Validator::make($request->all(), [
            'jenjang'=>'required|integer|between:1,3',
            'hari_libur'=>'nullable|array',
            'hari_libur.*'=>'required|integer|between:0,6|distinct',
            'jadwal'=>'required|array|between:1,7',
            'jadwal.*.jam_mulai'=>'nullable|date_format:H:i',
            'jadwal.*.jam_akhir'=>'nullable|date_format:H:i',
        ]);
        if($validator->fails())
        {
            dd($validator->errors());
            return back()
            ->with('error', [
                'icon'=>'error',
                'title'=>'Galat 422!',
                'text'=>'Input invalid'
            ]);
        }

        try
        {
            $validated=$validator->validated();
            $path="data/config/konfigurasi_jadwal_harian.json";
            if(!Storage::exists($path)) throw new \Exception("File tidak ditemukan", 404);

            $file=Storage::get($path);
            $content=json_decode($file, true);

            $hari_libur=range(0,6,1);
            $hari_libur=array_values(array_diff($hari_libur, $validated['hari_libur'] ?? []));
            // dd($hari_libur);
            $jenjang=$validated['jenjang'];

            $content[$jenjang]=['hari_libur'=>$hari_libur, 'jadwal'=>$validated['jadwal']];
            $content=json_encode($content);
            Storage::put($path, $content);

            return back()->with('success', [
                'icon'=>'success',
                'title'=>'Berhasil!',
                'text'=>'Jadwal berhasil diupdate'
            ]);
        }
        catch(\Exception $e)
        {
            return back()->with('error', [
                'icon'=>'error',
                'title'=>'Galat 500!',
                'text'=>$e->getMessage()
            ]);
        }
        // $validator=Validator::make($request->all(), [
        //     'hari_libur':
        // ])
    }
}
