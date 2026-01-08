<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\LogHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class LogHarianController extends Controller
{
    public static function middleware()
    {
        return ['auth:sanctum'];
    }
    public function logging(Request $request, LogHarian $log_harian)
    {
        // pengecekan kepemilikan log
        $log=$log_harian;
        if($log==null)
        {
            $response=['message'=>'Tidak ada kewajiban log harian'];
            return response()->json($response, Response::HTTP_NOT_FOUND);
        }
        $siswa=Auth::user()->siswa;
        if($log->id_siswa!=$siswa->nisn)
        {
            $response=['message'=>"Unauthorized. Log ini bukan milik siswa dengan id $siswa->nisn"];
            return response()->json($response, Response::HTTP_UNAUTHORIZED);
        }
        
        $validator=Validator::make($request->all(), [
            'label'=>'required|in:senang,marah,sedih,takut,jijik',
            'catatan'=>'nullable|max:255',
            'keterangan'=>'required|in:alpa,hadir,izin,sakit',
            'lampiran'=>'required_if:keterangan,izin,sakit',
            'swafoto'=>'required|images|mimes:png,jpg,jpeg|max:1024',
            'id_sesi_kbm'=>'required|exists:sesi_kbms'
        ]);
        if($validator->fails())
        {
            $response=
            [
                'message'=>'Inputs invalid',
                'errors'=>$validator->errors()
            ];
            return response()->json($response, 422);
        }

        DB::beginTransaction();
        try
        {
            $data=array_diff_key($validator->validated(), array_flip(['swafoto']));
            $file=$request->file('swafoto');
            if($file!=null)
            {
                $filename="log_".now()->format('dmYHis')."_".$siswa->nisn.$file->getClientOriginalExtension();
                $storage_path="/data/images/log_harian/$siswa->nisn/";
                Storage::disk('private')->put(file_get_contents($file), $storage_path.$filename);
                $data=[...$data,"swafoto_url"=>$storage_path.$filename];
            }
            $log->update($data);
            // LAKUKAN REQUEST KE API CEK DEPRESI DISINI. LAKUKAN QUERY TERLEBIH DAHULU

            DB::commit();
            return response()->json([
                'message'=>'Absensi berhasil dilakukan'
            ], 200);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            $response=['message'=>'Absensi gagal. Coba lagi nanti'];
            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
