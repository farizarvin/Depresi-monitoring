<?php

namespace App\Livewire\Kelas;

use App\Models\Kelas;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class EditForm extends Component
{
    public $form;
    public $id;
    protected $rules;
    public function __construct()
    {
        $form=[];
        $rules=
        [
            "nama"=>"required|unique:kelas,nama,",
            "jenjang"=>"required|integer|between:1,3",
            "jurusan"=>"required|in:rpl,dkv,tkr,tkj"
        ];
        foreach(array_keys($rules) as $field)
        {
            $form=[...$form, $field=>null];
        }
    
        $this->form=$form;
        $this->rules=$rules;
    }
    public function save()
    {
        $kelas=Kelas::find($this->id);
        if($kelas==null)
        {
            $this->dispatch('swal:alert', title : "Galat 404", text : "Kelas tidak ditemukan!", icon: "error");
            return;
        }
        
        $validator=Validator::make($this->form, [
            ...$this->rules,
            'nama'=>'required|unique:kelas,nama,'.$this->id
        ]);

        if($validator->fails())
        {
            $this->setErrorBag($validator->errors());
            $this->dispatch('swal:alert', title : "Galat 422", text : "Input invalid! Periksa kembali data yang anda masukkan", icon: "error");
            return;
        }
        $data=$validator->validated();
        $kelas->update($data);
        $this->dispatch('swal:alert', title : "Berhasil!", text : "Kelas berhasil diupdate", icon: "success");
        $this->dispatch('kelas:refresh');
        $this->dispatch('modal:close', modal_id:"edit-modal");

    }
    #[On('kelas-edit')]
    public function loadItem($id)
    {
        $item=Kelas::select(['nama', 'jenjang', 'jurusan'])->find($id);
        if($item==null) return;
        $item=$item->toArray();
        $this->id=$id;
        $this->form=$item;
    }
    
    public function render()
    {
        return view('livewire.kelas.edit-form');
    }
}
