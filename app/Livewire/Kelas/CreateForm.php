<?php

namespace App\Livewire\Kelas;

use App\Models\Kelas;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateForm extends Component
{
    use WithFileUploads;
    
    public $form;
    protected $rules;
    public function __construct()
    {
        $form=[];
        $rules=
        [
            "nama"=>"required|unique:kelas,nama",
            "jenjang"=>"required|integer|between:1,3",
            "jurusan"=>"required|in:rpl,dkv,tkr,tkj"
        ];
        foreach(array_keys($rules) as $field)
        {
            $form=[...$form, $field=>null];
        }
        $form['jenjang']="1";
        $form['jurusan']="rpl";

        $this->form=$form;
        $this->rules=$rules;
    }
    public function save()
    {
        
        $validator=Validator::make($this->form, $this->rules);
        if($validator->fails())
        {
            $this->setErrorBag($validator->errors());
            $this->dispatch('swal:alert', title : "Galat 422", text : "Input invalid! Periksa kembali data yang anda masukkan", icon: "error");
            return;
        }
        $data=$validator->validated();
        Kelas::create($data);
        $this->dispatch('swal:alert', title : "Berhasil!", text : "Kelas baru berhasil ditambahkan", icon: "success");
        $this->dispatch('kelas:refresh');
        $this->dispatch('modal:close', modal_id:"create-modal");
    }
    public function render()
    {
        return view('livewire.kelas.create-form');
    }
}
