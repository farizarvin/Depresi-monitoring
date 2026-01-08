<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Siswa;
use App\Models\RiwayatKelas;
use Illuminate\Support\Facades\Validator;


class SiswaForm extends Component
{
    public $form;
    protected $rules;
    public function __construct()
    {
        $current_date=now()->format('Y-m-d');
        $this->form=[];
        $this->rules=
        [
            "form.nama_lengkap"=>"required|max:50",
            "form.nisn"=>"required|unique:siswas|digits:10",
            'form.gender'=>'required|boolean',
            "form.tanggal_lahir"=>"required|date_format:Y-m-d|before:$current_date",
            "form.alamat"=>"required|max:255",
            "form.id_kelas"=>"required|exists:kelas,id",
            "form.avatar"=>"required|image|mime:jpg,png,jpeg|max:512",
            "form.id_thak_masuk"=>
            [
                "required",
                Rule::exists('tahun_akademik', 'id')->where('status', true)
            ]

        ];
        foreach(array_keys($this->rules) as $field)
        {
            $this->form=[...$this->form, $field=>null];
        }
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
        DB::beginTransaction();
        try
        {
            $data=$validator->validated();
            $storage_path=null;
            $file=$this->form['avatar'];
            if($file!=null)
            {
                $filename=uniqid().'_'.now()->format('dmY').$file->getClientOriginalExtension();
                $storage_path='/data/images/avatars/'.$filename;
                Storage::disk('private')->put($storage_path, file_get_contents($file));
            }

            // Create user
            $tgl_lahir=Carbon::parse($data['tanggal_lahir'])->format('dmY');
            $data_user=
            [
                'role'=>'siswa',
                'email'=>$data['email'],
                'username'=>$data['nisn'],
                'avatar_url'=>$storage_path,
                'password'=>'Nubi-'.$tgl_lahir,
            ];
            $user=User::create($data_user);

            $data_siswa=
            [
                ...$data, 
                'id_user'=>$user->id,
            ];
            $siswa=Siswa::create($data_siswa);

            // Create riwayat kelas
            $data_riwayat=
            [
                'id_kelas'=>$data['id_kelas'],
                'id_siswa'=>$siswa->id,
                'id_thak'=>$data['id_thak'],
                'status'=>'MM'
            ];
            RiwayatKelas::create($data_riwayat);

            DB::commit();
            $this->dispatch('swal:alert', title : "Berhasil!", text : "Siswa berhasil ditambahkan", icon: "success");
        }
        catch(\Exception $e)
        {

        }
    }
    public function render()
    {
        return view('livewire.siswa-form');
    }
}
