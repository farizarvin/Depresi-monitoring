<?php

namespace App\Livewire\Siswa\Kehadiran;

use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TableData extends Component
{
    use WithPagination;

    public $siswa, $presensi, $thak;
    public function mount()
    {
        $this->loadData();
    }
    public function loadData()
    {
        $thak=TahunAkademik::where('current', true)->first();
        $siswa=Siswa::select(['id', 'nisn', 'nama_lengkap'])->get();
        $sub1=DB::table('presensi')
        ->selectRaw("
            id_siswa,
            COUNT(id) as total_presensi,
            SUM(CASE WHEN status='H' THEN 1 ELSE 0 END) as total_hadir,
            SUM(CASE WHEN status='A' THEN 1 ELSE 0 END) as total_alpha
        ")
        ->where('id_thak', $thak->id)
        ->whereIn('id_siswa', $siswa->pluck('id')->toArray())
        ->groupBy('id_siswa');
        $sub2=DB::table(DB::raw("({$sub1->toSql()}) as a"))
        ->mergeBindings($sub1)
        ->selectRaw("
            a.id_siswa,
            a.total_presensi,
            a.total_hadir,
            a.total_alpha,
            (a.total_presensi - (a.total_hadir+a.total_alpha)) as total_ijin_sakit,
            ROUND((a.total_hadir/a.total_presensi) * 100, 2) as persen_hadir,
            ROUND((a.total_alpha/a.total_presensi) * 100, 2) as persen_alpha
        ");

        $presensi=DB::table(DB::raw("({$sub2->toSql()}) as b"))
        ->mergeBindings($sub2)
        ->selectRaw("
            *,
            ROUND(100 - (persen_hadir + persen_alpha), 2) as persen_ijin_sakit
        ")
        ->get()
        ->keyBy('id_siswa')
        ->toArray();
        

        $this->thak=$thak;
        $this->siswa=$siswa;
        $this->presensi=$presensi;
    }
    public function render()
    {
        
        return view('livewire.siswa.kehadiran.table-data');
    }
}
