<?php

namespace App\Livewire\Siswa;

use App\Models\Kelas;
use Livewire\Component;
use App\Models\User;
use App\Models\Siswa;
use Livewire\Attributes\On;
use App\Models\RiwayatKelas;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\WithFileUploads;

class CreateForm extends Component
{
    use WithFileUploads;


    public $form, $thak, $kelas;
    protected $rules;
    public function __construct()
    {
        $current_date=now()->format('Y-m-d');
        $this->form=[];
        $this->rules=
        [
            "nama_lengkap"=>"required|max:50",
            "email"=>"required|email",
            "nisn"=>"required|unique:siswa|digits:10",
            'gender'=>'required|boolean',
            'tempat_lahir'=>"required|max:50",
            "tanggal_lahir"=>"required|date_format:Y-m-d|before:$current_date",
            "alamat"=>"required|max:255",
            "id_kelas"=>"required|exists:kelas,id",
            "avatar"=>"required|image|mimes:jpg,png,jpeg|max:512",
            "status"=>"required|in:NW,MM",
            "id_thak_masuk"=>
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
            $file=$this->form['avatar'];
            $filename=$file ? uniqid().'_'.now()->format('dmY').$file->getClientOriginalExtension() : "";

            // Create user
            $tgl_lahir=Carbon::parse($data['tanggal_lahir'])->format('dmY');
            $data_user=
            [
                'role'=>'siswa',
                'email'=>$data['email'],
                'username'=>$data['nisn'],
                'avatar_url'=>$filename,
                'password'=>'Nubi-'.$tgl_lahir,
            ];
            $user=User::create($data_user);

            $data_siswa=
            [
                ...$data, 
                'id_user'=>$user->id,
            ];
            $data_siswa=array_diff_key($data_siswa, array_flip(['status', 'avatar']));
            $siswa=Siswa::create($data_siswa);

            // Create riwayat kelas
            $data_riwayat=
            [
                'id_kelas'=>$data['id_kelas'],
                'id_siswa'=>$siswa->id,
                'id_thak'=>$data['id_thak_masuk'],
                'status'=>$data['status'],
                'active'=>true
            ];
            RiwayatKelas::create($data_riwayat);

            
            if($file!=null)
            {
                $path="app/data/images/users/$user->id/";
                $file->storeAs($path, $filename, 'private');
            }
            DB::commit();
            $this->dispatch('swal:alert', title : "Berhasil!", text : "Siswa berhasil ditambahkan", icon: "success");
            $this->dispatch('modal:close', modal_id : 'create-modal');
            $this->dispatch('siswa:refresh');
        }
        catch(\Exception $e)
        {
            $this->dispatch('swal:alert', title : 'Galat 500', text : $e->getMessage(), icon : 'error');
        }
    }
    #[On('siswa:create')]
    public function create()
    {
        $this->thak=TahunAkademik::select(['id','nama_tahun'])->where('status', true)->get();
        $this->kelas=Kelas::select(['id','nama'])->orderBy('jenjang', 'asc')->orderBy('nama', 'asc')->get();
        if(!$this->thak || !$this->kelas) return;
        $this->form['id_thak_masuk']=$this->thak->first()->id;
        $this->form['id_kelas']=$this->kelas->first()->id;
        $this->form['status']='NW';
    }
    public function render()
    {
        return view('livewire.siswa.create-form');
    }
}
