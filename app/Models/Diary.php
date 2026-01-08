<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Diary extends Model
{
    //
    protected $guarded = ['id'];
    protected $table='diary';

    private static $mentalHealthCols=['id', 'id_presensi', 'emoji','swafoto', 'swafoto_pred', 'catatan_pred', 'catatan', 'catatan_ket', 'waktu'];

    public $timestamps = false;
    public function attendance() : BelongsTo
    {
        return $this->belongsTo(Presensi::class, 'id_presensi', 'id');
    }

    public static function getMentalHealthData($year, $students, $range)
    {
        $base=DB::table('diary')
            ->join('presensi', 'presensi.id', '=', 'diary.id_presensi')
            ->selectRaw(
                "diary.*, 
                presensi.id_siswa, presensi.id_thak,
                ROW_NUMBER() OVER (
                    PARTITION BY presensi.id_siswa
                    ORDER BY diary.waktu DESC
                ) AS rn
            ")
            ->where('id_thak', $year)
            ->whereIn('id_siswa', $students);
       
        $data=DB::table(DB::raw("({$base->toSql()}) as a"))
            ->mergeBindings($base)
            ->where('rn', '<=', $range)
            ->orderByDesc('waktu')
            ->get()
            ->groupBy('id_siswa');
        
        return $data;
    }
}
