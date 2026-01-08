<?php

namespace App\Livewire\Siswa;

use App\Models\Siswa;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class TableData extends Component
{
    public $form;
    public $siswa;
    public function mount()
    {
        $this->loadData();
    }
    #[On('siswa:refresh')]
    public function loadData()
    {
        $this->siswa=Siswa::where('status', true)->get();
    }
    #[On('siswa:delete')]
    public function destroy($id)
    {
        
       
        DB::beginTransaction();
        try
        {
            $item=Siswa::findOrFail($id);
            $driver=config("directories.user");
            $user=$item->user;
            $filepath=$driver['url']."/".($user?->avatar_url || $user->avatar_url=="" ? "p" : $user?->avatar_url);
            
            $item->riwayat_kelas()->delete();
            $item->user()->delete();
            $item->delete();
            Storage::disk('private')->delete($filepath);
            
            DB::commit();
            $this->dispatch('swal:alert', title : "Berhasil!", text : "Siswa berhasil dihapus", icon : "success");
            $this->loadData();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            $this->dispatch('swal:alert', title : 'Galat '.$e->getCode() , text : $e->getMessage(), icon : 'error');
        }
    }
    public function render()
    {
        return view('livewire.siswa.table-data');
    }
}
