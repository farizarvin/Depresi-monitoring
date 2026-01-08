<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Dass21Hasil;
use App\Models\Diary;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Storage;

class LoginController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return 
        [
            new Middleware('guest', except : ['postLogout']),
            new Middleware('auth', only : ['postLogout']),
            
        ];
    }
    public function index()
    {
        return view('auth.sanctum_login');
    }
    public function postLogin(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'username'=>'required',
            'password'=>'required'
        ]);
        if($validator->fails())
        {
            $response=
            [
                'msg'=>'Input salah.',
                'errs'=>$validator->errors()
            ];
            return back()->withErrors($response);
        }

        
        
        
        $credentials=$validator->validated();
        if(Auth::guard('web')->attempt($credentials, $request->remember))
        {
            $request->session()->regenerate();
            $user=auth('web')->user();
            $role=$user->role;

            $this->setQuetionaryStatus($user);
            return redirect("/$role/dashboard");
        }
        $response=['credential'=>'username/password salah'];
        return back()->withErrors($response);
    }
    public function postLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function setQuetionaryStatus($user)
    {
        $siswa=$user?->siswa;

        if($user->role==="siswa" && (bool) $siswa?->need_survey===false)
        {

            try
            {
                $yearId=TahunAkademik::orderByRaw('(3 - current - status) ASC')->orderBy('nama_tahun', 'desc')->first()?->id;
                $lastRecap=Dass21Hasil::where('id_siswa', $siswa->id)->latest('created_at')->first();

                if($yearId==null) dd("Tahun tidak ditemukan");

                $path="data/config/konfigurasi_rekap_mental.json";
                if(!Storage::exists($path))
                    throw new \Exception("File konfigurasi tidak ditemukan", 500);

                $config=Storage::get($path);
                $config=json_decode($config, true);
                $range=(int) $config['rentang'];
                $threshold=(int) $config['threshold'];
                $subQuery = 
                $lastRecap===null? 
                function($query) use($siswa, $yearId) {
                    return $query->where('id_thak', $yearId)->where('id_siswa', $siswa->id);
                }:
                function($query) use($siswa, $yearId, $lastRecap) {
                    return $query->where('id_thak', $yearId)->where('id_siswa', $siswa->id)->where('waktu', $lastRecap->created_at);
                };

                $mentalData=Diary::orderBy('waktu', 'desc')->whereHas('attendance', $subQuery)->limit($range)->get();
                
                $depressionRate=$mentalData->reduce(function($acc, $row) {
                    $swafoto_pred=strtolower($row->swafoto_pred);
                    $catatan_pred=strtolower($row->catatan_pred);
                    $bool=($catatan_pred==='terindikasi depresi' && !in_array($swafoto_pred, ['happy', 'surprise']));
                    return $acc + (int) $bool;
                }, 0);

                // dd($depressionRate);
                $depressionRate=($depressionRate/$range)*100;
                $needSurvey=$depressionRate >= $threshold && $mentalData->count() >= $range;
                $siswa->need_survey=$needSurvey;

                if(!$needSurvey && $mentalData->count() >= $range)
                {
                    $siswa->is_depressed=false;
                    $siswa->need_selfcare=false;
                }
                $siswa->save();
            }
            catch(\Exception $e)
            {
                
                dd($e->getMessage());
            }
        }
    }
}
