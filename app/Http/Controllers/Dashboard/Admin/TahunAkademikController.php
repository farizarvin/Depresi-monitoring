<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TahunAkademikController extends Controller
{
    public static function middleware()
    {
        return ['auth', 'role:admin'];
    }
    public function index()
    {
        $thak=TahunAkademik::all()->sortBy('nama_tahun');
        return view('admin.tahun_akademik.index', compact('thak'));
    }
    public function create()
    {

    }
    public function show(TahunAkademik $thak)
    {
        if($thak==null)
        {
            return;
        }

        return;
    }
    private function getCurrentStatus($data)
    {
        if(isset($data['current'])) return $data['current'];
        return TahunAkademik::exists()
                ->where('current',true);
    }
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'status'=>'required|boolean',
            'tanggal_mulai'=>'required|date_format:Y-m-d',
            'tanggal_selesai'=>
            [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:'.Carbon::parse($request->tanggal_mulai ?? now()->format('Y-m-d'))
                                ->addYear()
                                ->format('Y-m-d')
            ],
            'current'=>
            [
                'bail',
                'required_if:status,1',
                'boolean',
                Rule::when(
                    ($request->status ?? 0) == 0,
                    'not_in:1',
                    null
                ),
                Rule::unique('tahun_akademik', 'current')
                ->where('status', true)
                ->where('current', true)
            ],
            
        ]);

        if($validator->fails())
        {
            return;
        }
        $data=$validator->validated();
        $tahun_1=Carbon::parse($data['tahun_mulai'])->format('Y');
        $tahun_2=Carbon::parse($data['tahun_selesai'])->format('Y');
        $nama_tahun="$tahun_1/$tahun_2";
        if(TahunAkademik::exists()->where('nama_tahun', $nama_tahun))
        {
            return;
        }

        $data['current']=$this->getCurrentStatus($data);
        $data_thak=
        [
            ...$data,
            'nama_tahun'=>$nama_tahun,
        ];
        TahunAkademik::create($data_thak);
        return;
    }
    
    public function update(Request $request, TahunAkademik $thak)
    {
        if($thak==null)
        {
            return;
        }
        $id=$thak->id;
        $validator=Validator::make($request->all(), [
            'status'=>'required|boolean',
            'tanggal_mulai'=>'required|date_format:Y-m-d',
            'tanggal_selesai'=>
            [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:'.Carbon::parse($request->tanggal_mulai ?? now()->format('Y-m-d'))
                                ->addYear()
                                ->format('Y-m-d')
            ],
            'current'=>
            [
                'bail',
                'required_if:status,1',
                'boolean',
                Rule::when(
                    ($request->status ?? 0) == 0,
                    'not_in:1',
                    null
                ),
                Rule::unique('tahun_akademik', 'current')
                ->ignore($id, 'id')
                ->where('status', true)
                ->where('current', true)
            ],
            
        ]);

        if($validator->fails())
        {
            return;
        }
        $data=$validator->validated();
        $tahun_1=Carbon::parse($data['tahun_mulai'])->format('Y');
        $tahun_2=Carbon::parse($data['tahun_selesai'])->format('Y');
        $nama_tahun="$tahun_1/$tahun_2";
        if(TahunAkademik::exists()->where('nama_tahun', $nama_tahun))
        {
            return;
        }
        
        $data['current']=$this->getCurrentStatus($data);
        $data_thak=
        [
            ...$data,
            'nama_tahun'=>$nama_tahun,
        ];
        $thak->update($data_thak);
        return;
    }
    public function destroy(TahunAkademik $thak)
    {
        if($thak==null)
        {
            return;
        }
        $thak->delete();
        return;
    }
}
