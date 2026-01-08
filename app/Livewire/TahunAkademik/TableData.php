<?php

namespace App\Livewire\TahunAkademik;

use App\Models\TahunAkademik;
use Livewire\Component;
use Livewire\Attributes\On;

class TableData extends Component
{
    public $thak;
    public function mount()
    {
        $this->loadData();
    }
    public function loadData()
    {
        $this->thak=TahunAkademik::orderBy('tanggal_mulai', 'desc')->get();
    }
    #[On('tahun_akademik:delete')]
    public function deleteKelas($id)
    {
        $item=TahunAkademik::find($id);
        if($item==null) return;
        $item->delete();
        $this->dispatch('swal:alert', title : "Berhasil!", text : "Tahun akademik berhasil dihapus", icon : "success");
        $this->loadData();
    }
    #[On('tahun_akademik:refresh')]
    public function refreshThak()
    {
        $this->loadData();
    }
    public function render()
    {
        return view('livewire.tahun-akademik.table-data');
    }
}
