<?php

namespace App\Livewire\TahunAkademik;

use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\TahunAkademik;

class CreateForm extends Component
{
    public $form;
    protected $rules;

    public function __construct()
    {
        $form=[];
        $rules=$this->getRules();
        foreach(array_keys($rules) as $key)
            $form=[...$form, $key=>null];
        $form['current']=$form['status']=false;
        $this->form=$form;
    }
    public function save()
    {
        $rules=$this->getRules();
        $validator=Validator::make($this->form, $rules);
        if($validator->fails())
        {
            $this->setErrorBag($validator->errors());
            $this->dispatch('swal:alert', title : "Galat 422", text : "Input invalid! Periksa kembali data yang anda masukkan", icon: "error");
            return;
        }

        $data=$validator->validated();
        $data=[...$data, 'nama_tahun'=>$data['tahun_mulai'].'/'.$data['tahun_akhir']];
        $data=array_diff_key($data, array_flip(['tahun_mulai', 'tahun_akhir']));

        if(TahunAkademik::where('nama_tahun', $data['nama_tahun'])->exists())
        {
            $this->setErrorBag(['periode'=>'Tahun akademik sudah ada']);
            return;
        }

        $data['current']=$this->getCurrentStatus($data);
        TahunAkademik::create($data);
        $this->dispatch('swal:alert', title : "Berhasil!", text : "Tahun akademik baru berhasil ditambahkan", icon: "success");
        $this->dispatch('tahun_akademik:refresh');
        $this->dispatch('modal:close', modal_id:"create-modal");
    }
    private function getCurrentStatus($data)
    {
        if(isset($data['current'])) return $data['current'];
        return TahunAkademik::exists()
                ->where('current',true);
    }
    public function getRules()
    {
        $form=$this->form ?? [];
        $rules=
        [
            'status'=>'required|boolean',
            'tahun_mulai'=>'required|date_format:Y',
            'tahun_akhir'=>'required|date_format:Y|after:tahun_mulai',
            'tanggal_mulai'=>[
                'bail',
                'required',
                'date_format:Y-m-d',
                function($attr, $val, $fail) use($form)
                {
                    if($form['tahun_mulai']==null||$val==null) return;
                    $year1=$form['tahun_mulai'];
                    $year2=Carbon::parse($val)->format('Y');

                    if($year1!=$year2)
                        $fail("$attr harus berada di tahun $year1");
                }
            ],
            'tanggal_selesai'=>[
                'bail',
                'required',
                'date_format:Y-m-d',
                function($attr, $val, $fail) use($form)
                {
                    if($form['tahun_akhir']==null||$val==null) return;
                    $year1=$form['tahun_akhir'];
                    $year2=Carbon::parse($val)->format('Y');

                    if($year1!=$year2)
                        $fail("$attr harus berada di tahun $year1");
                }
            ],
            'current'=>
            [
                'bail',
                'required_if:status,1',
                'boolean',
                Rule::when(
                    ($form['status'] ?? 0) == 0,
                    'not_in:1',
                    null
                ),
                Rule::unique('tahun_akademik', 'current')
                ->where('status', true)
                ->where('current', true)
            ],
        ];
        return $rules;
    }
    public function render()
    {
        return view('livewire.tahun-akademik.create-form');
    }
}
