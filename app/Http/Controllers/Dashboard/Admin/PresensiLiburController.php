<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\PresensiLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PresensiLiburController extends Controller
{
    // public static function middleware()
    // {
    //     return ['auth', 'role:admin'];
    // }
    private function getHrBlnIni()
    {
        $month=now()->month;
        $years=now()->year;

        $start=Carbon::create($years, $month, 1);
        $end=$start->copy()->endOfMonth();

        $index=0;
        $calendar=[];
        foreach(Carbon::parse($start)->toPeriod($end) as $date)
        {
            $day=$date->dayOfWeek;
            if($day==0||count($calendar)==0)
            {
                $index=count($calendar);
                array_push($calendar, []);
            }
            array_push($calendar[$index], [
                'date'=>$date->day,
                'day'=>$day
            ]);
        }
        return $calendar;
    }

    public function getData()
    {
        $days=$this->getHrBlnIni();
        $vacations=PresensiLibur::all()->sortBy('tanggal_mulai');

        return response()->json(compact('days', 'vacations'), 200);
    }



    public function index()
    {
        $days=$this->getHrBlnIni();
        $lbr=PresensiLibur::all()->sortBy('tanggal_mulai');
        return view('admin.hari_libur.index', compact('lbr', 'days'));
    }
    
    // public function show(PresensiLibur $lbr)
    // {
    //     if($lbr==null)
    //     {
    //         return;
    //     }

    //     return;
    // }
    public function store(Request $request)
    {
        // dd($request->all());
        $validator=Validator::make($request->all(), [
            'ket'=>'required|max:255',
            'tanggal_mulai'=>'required|integer',
            'tanggal_selesai'=>'nullable|integer',
            'bulan_mulai'=>'required|integer|between:1,12',
            'bulan_selesai'=>'nullable|integer|between:1,12',
            'jenjang'=>'required|array',
            'jenjang.*'=>'required|between:1,3|distinct'
        ]);
    
        if($validator->fails())
        {
            return back()->withError($validator->errors())
            ->withInput()
            ->with('error', [
                'icon'=>'error',
                'title'=>'Galat 404!',
                'text'=>'Input invalid'
            ]);
        }


        $user=auth('web')->user();
        $data=$validator->validated();
        $data['jenjang'] = json_encode($data['jenjang']);
        $data_lbr=
        [
            ...$data,
            'id_author'=>$user->id,
            'tanggal_selesai'=>$data['tanggal_selesai'] ?? $data['tanggal_mulai'],
            'bulan_selesai'=>$data['bulan_selesai'] ?? $data['bulan_mulai']
        ];
        PresensiLibur::create($data_lbr);
        return redirect()->route('admin.dashboard')->with('success', 'Hari libur berhasil ditambahkan');
    }
    public function update(Request $request, PresensiLibur $lbr)
    {
        if($lbr==null)
        {
            return;
        }
            
        $user=auth('web')->user();
        if($user->id != $lbr->id_author)
        {
            return;
        }

        $validator=Validator::make($request->all(), [
            'ket'=>'required|max:255',
            'tanggal_mulai'=>'required|integer',
            'tanggal_selesai'=>'nullable|date_format:d-m|after:tanggal_mulai',
            'bulan_mulai'=>'required|integer|between:1,12',
            'bulan_selesai'=>'nullable|integer|between:1,12',
            'jenjang'=>'required|array',
            'jenjang.*'=>'required|between:1,3|distinct'
        ]);
        if($validator->fails())
        {
            return back()->with('error', '');
        }
        
        $data=$validator->validated();
        $data_lbr=
        [
            ...$data,
            'tanggal_selesai'=>$data['tanggal_selesai'] ?? $data['tanggal_mulai'],
        ];
        $lbr->update($data_lbr);
        return;
    }
    public function destroy(Request $request)
    {
       
        $validator=Validator::make($request->all(), [
            'id'=>'required|exists:presensi_libur,id',
            'date'=>'required|date_format:d-m'
        ]);
        if($validator->fails())
        {
            return back()->withError($validator->errors())
            ->withInput()
            ->with('error', [
                'icon'=>'error',
                'title'=>'Galat 404!',
                'text'=>'Input invalid'
            ]);
        }
        $validated=$validator->validated();
        $pl=PresensiLibur::find($validated['id']);
        $date=$validated['date'];


        $dateStart=Carbon::createfromFormat('d-m', "$pl->tanggal_mulai-$pl->bulan_mulai");
        $dateEnd=Carbon::createfromFormat('d-m', "$pl->tanggal_selesai-$pl->bulan_selesai");
        $date=Carbon::createfromFormat('d-m', $date);

        if(!$date->isBetween($dateStart, $dateEnd, true))
        {
            
            return back()
            ->with('error', [
                'icon'=>'error',
                'title'=>'Galat 422!',
                'text'=>'Tanggal invalid'
            ]);
        }

        
        DB::beginTransaction();
        $base=$pl->only(['ket', 'id_author', 'jenjang']);
        
        try
        {
            if($date->notEqualTo($dateStart) && $date->notEqualTo($dateEnd))
            {
                $date->subDay();
                $monthStart1=$dateStart->month;
                $monthEnd1=$date->month;
                $dayStart1=$dateStart->day;
                $dayEnd1=$date->day;

                $date->addDays(2);
                $monthStart2=$date->month;
                $monthEnd2=$dateEnd->month;
                $dayStart2=$date->day;
                $dayEnd2=$dateEnd->day;
                
                $data1=
                [
                    ...$base,
                    'bulan_mulai'=>$monthStart1,
                    'bulan_selesai'=>$monthEnd1,
                    'tanggal_mulai'=>$dayStart1,
                    'tanggal_selesai'=>$dayEnd1,
                ];
                $data2=
                [
                    ...$base,
                    'bulan_mulai'=>$monthStart2,
                    'bulan_selesai'=>$monthEnd2,
                    'tanggal_mulai'=>$dayStart2,
                    'tanggal_selesai'=>$dayEnd2,
                ];
                PresensiLibur::insert([$data1, $data2]);
            }
            else
            {
                if($date->equalTo($dateStart)) $dateStart->addDay();
                if($date->equalTo($dateEnd)) $dateEnd->subDay();

                $monthStart=$dateStart->month;
                $monthEnd=$dateEnd->month;
                $dayStart=$dateStart->day;
                $dayEnd=$dateEnd->day;
                $data=
                [
                    'bulan_mulai'=>$monthStart,
                    'bulan_selesai'=>$monthEnd,
                    'tanggal_mulai'=>$dayStart,
                    'tanggal_selesai'=>$dayEnd,
                    ...$base
                ];
                PresensiLibur::create($data);
            }
            $pl->delete();
            DB::commit();
            return back()->with('success', [
                'icon'=>'success',
                'title'=>'Berhasil!',
                'text'=>'Tanggal berhasil dihapus!'
            ]);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return back()
            ->with('error', [
                'icon'=>'error',
                'title'=>'Galat 500!',
                'text'=>$e->getMessage()
            ]);
        }


    }
}
