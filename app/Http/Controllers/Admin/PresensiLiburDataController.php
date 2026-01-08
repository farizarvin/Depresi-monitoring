<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\Admin\PresensiLiburController;
use App\Models\PresensiLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PresensiLiburDataController extends Controller
{
    private function getAllDays()
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
            if($day==0 ||count($calendar)==0)
            {
                $index=count($calendar);
                array_push($calendar, []);
            }
            array_push($calendar[$index], ['date'=>$date->day,'day'=>$day]);
        }
        return $calendar;
    }
    public function index()
    {
        $days=$this->getAllDays();
        $vacations=PresensiLibur::all()->sortBy('tgl_mulai');

        return response()->json(compact('days', 'vacations'), 200);
    }
}
