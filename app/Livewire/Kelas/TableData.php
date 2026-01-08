<?php

namespace App\Livewire\Kelas;

use Livewire\Component;
use App\Models\Kelas;
use Livewire\Attributes\On;

class TableData extends Component
{
    public $kelas;
    public function mount()
    {
        $this->loadData();
    }
    public function loadData()
    {
        $this->kelas=Kelas::orderBy('nama', 'asc')->orderBy('jenjang', 'asc')->get();
    }
    #[On('kelas:delete')]
    public function deleteKelas($id)
    {
        $item=Kelas::find($id);
        if($item==null) return;
        $item->delete();
        $this->dispatch('swal:alert', title : "Berhasil!", text : "Kelas berhasil dihapus", icon : "success");
        $this->loadData();
    }
    #[On('kelas:refresh')]
    public function refreshKelas()
    {
        $this->loadData();
    }
    public function render()
    {
        return view('livewire.kelas.table-data');
    }
}
