<?php

namespace App\Livewire\Siswa\Kehadiran;

use App\Models\Siswa;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\TahunAkademik;
use App\Models\Presensi;

class DetailView extends Component
{
    public $id, $siswa, $presensi;
    #[On('siswa_kehadiran:view')]
    public function loadData($id)
    {
        $siswa=Siswa::find($id);
        $thak=TahunAkademik::where('current', true)->first();
        $presensi=Presensi::where('id_thak', $thak->id)
        ->where('id_siswa', $siswa->id)
        ->get();

        $this->siswa=$siswa;
        $this->presensi=$presensi;
    }
    public function render()
    {
        return view('livewire.siswa.kehadiran.detail-view');
    }
}
