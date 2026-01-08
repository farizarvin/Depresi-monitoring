<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Presensi extends Model
{
    //
    protected $guarded=['id'];
    protected $table='presensi';
    public $timestamps = false;

    protected $casts=[
        'waktu'=>'datetime'
    ];

    public function diary()
    {
        return $this->hasOne(Diary::class, 'id_presensi', 'id');
    }

    public static function getAttendanceCalc($year, $students)
    {
        
        // $details=DB::table('presensi')
        //     ->selectRaw('
        //         *,
        //         ROW_NUMBER() OVER (
        //             PARTITION BY id_siswa
        //         ) AS rn
        //     ')
        //     ->where('id_thak', $year)
        //     ->whereIn('id_siswa', $students)
        //     ->get()
        //     ->groupBy('id_siswa')
        //     ->map(fn($item)=>$item->chunk(10));
        // dd($details);
        $details=null;
        $baseResult1=DB::table('presensi')
            ->selectRaw("
                id_siswa,
                COUNT(id) as total_presensi,
                SUM(CASE WHEN status='H' THEN 1 ELSE 0 END) as total_hadir,
                SUM(CASE WHEN status='A' THEN 1 ELSE 0 END) as total_alpha
            ")
            ->where('id_thak', $year)
            ->whereIn('id_siswa', $students)
            ->groupBy('id_siswa');
        $baseResult2=DB::table(DB::raw("({$baseResult1->toSql()}) as a"))
            ->mergeBindings($baseResult1)
            ->selectRaw("
                a.id_siswa,
                a.total_presensi,
                a.total_hadir,
                a.total_alpha,
                (a.total_presensi - (a.total_hadir+a.total_alpha)) as total_ijin_sakit,
                ROUND((a.total_hadir/a.total_presensi) * 100, 2) as persen_hadir,
                ROUND((a.total_alpha/a.total_presensi) * 100, 2) as persen_alpha
            ");

        $results=DB::table(DB::raw("({$baseResult2->toSql()}) as b"))
            ->mergeBindings($baseResult2)
            ->selectRaw("
                *,
                ROUND(100 - (persen_hadir + persen_alpha), 2) as persen_ijin_sakit
            ")
            ->get()
            ->keyBy('id_siswa');
        return [$results, $details];
    }

}
