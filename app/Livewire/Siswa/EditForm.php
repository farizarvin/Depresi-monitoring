<?php

namespace App\Livewire\Siswa;

use App\Models\Siswa;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\TahunAkademik;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\RiwayatKelas;
use Illuminate\Support\Facades\URL;
use Livewire\WithFileUploads;

class EditForm extends Component
{
    use WithFileUploads;

    public $id_siswa, $id_user;
    public $form, $thak, $kelas;
    public function __construct()
    {
        $form=[];
        $rules=$this->generateRules();
        foreach(array_keys($rules) as $rule)
            $form=[...$form, $rule=>null];

        $form['avatar_url']=null;
        $this->form=$form;
    }
    private function generateFileName($file)
    {
        return uniqid().'_'.now()->format('dmY').".".$file->getClientOriginalExtension();
    }
    public function save()
    {
        DB::beginTransaction();
        try
        {
            $siswa=Siswa::findOrFail($this->id_siswa);
            $user=$siswa->user;
            $rules=$this->generateRules();
            $validator=Validator::make($this->form, $rules);
            
            if($validator->fails())
            {
                $this->setErrorBag($validator->errors());
                throw new \Exception("Input invalid! periksa kembali data yang anda masukkan", 422);
                return;
            }
            $data=$validator->validated();
            
            $oldfile=$user->avatar_url;
            $file=$this->form['avatar'] ?? null;
            $filename=$file ? $this->generateFileName($file) : $user->avatar_url;
            
            

            // Create user
            
            $tgl_lahir=Carbon::parse($data['tanggal_lahir'])->format('dmY');
            $data_user=
            [
                'email'=>$data['email'],
                'username'=>$data['nisn'],
                'avatar_url'=>$filename,
                'password'=>'Nubi-'.$tgl_lahir,
            ];
            $user->update($data_user);

            $data_siswa=array_diff_key($data, array_flip(['status', 'avatar']));
            $siswa->update($data_siswa);

            // Create riwayat kelas
            if($siswa->getKelasAktif()?->id_kelas != $data['id_kelas'])
            {
                $data_riwayat=
                [
                    'id_kelas'=>$data['id_kelas'],
                    'id_siswa'=>$siswa->id,
                    'id_thak'=>$data['id_thak_masuk'],
                    'status'=>$data['status'],
                    'active'=>true
                ];
                $siswa->kelas_aktif()->update(['active'=>false, 'status'=>'CL']);
                RiwayatKelas::create($data_riwayat);
            }

            

            if($file!=null)
            {
                $path="app/data/images/users/$user->id/";
                $oldpath="$path/$oldfile";
                $file->storeAs($path, $filename, 'private');
                Storage::disk('private')->delete($oldpath);
            }
            DB::commit();
            
            $this->dispatch('swal:alert', title : "Berhasil!", text : "Siswa berhasil diupdate", icon: "success");
            $this->dispatch('modal:close', modal_id : 'edit-modal');
            $this->dispatch('siswa:refresh');
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            $this->dispatch('swal:alert', title : "Galat ".$e->getCode(), text : $e->getMessage(), icon: "error");
        }
    }
    public function generateRules()
    {
        $current_date=now()->format('Y-m-d');
        $rules=
        [
            "nama_lengkap"=>"required|max:50",
            "email"=>"required|email",
            "nisn"=>"required|unique:siswa,nisn,$this->id_siswa,id|digits:10",
            'gender'=>'required|boolean',
            'tempat_lahir'=>"required|max:50",
            "tanggal_lahir"=>"required|date_format:Y-m-d|before:$current_date",
            "alamat"=>"required|max:255",
            "id_kelas"=>"required|exists:kelas,id",
            "avatar"=>"nullable|image|mimes:jpg,png,jpeg|max:512",
            "status"=>"required|in:NW,MM",
            "id_thak_masuk"=>
            [
                "required",
                Rule::exists('tahun_akademik', 'id')->where('status', true)
            ]
        ];
        return $rules;
    }

    
    #[On('siswa:edit')]
    public function edit($id)
    {
        try
        {
            $siswa=Siswa::findOrFail($id);
            $user=$siswa->user;
            $ri_kl=$siswa->riwayat_kelas()->where('active', true)->first() ?? $siswa->riwayat_kelas()->latest()->first();
            $this->thak=TahunAkademik::select(['id','nama_tahun'])->where('status', true)->get();
            $this->kelas=Kelas::select(['id','nama'])->orderBy('jenjang', 'asc')->orderBy('nama', 'asc')->get();

            
            if(!$this->thak || !$this->kelas) 
                throw new \Exception('Tahun or kelas is empty', 404);
            
            $this->id_siswa=$id;
            $this->id_user=$siswa->id_user;
            
            // dd($id);
            $this->form=
            [
                ...array_diff_key($siswa->toArray(), array_flip(['id', 'created_at', 'updated_at', 'id_user'])),
                'email'=>$user->email,
                'avatar_url'=>$user->avatar_url,
                'id_kelas'=>$ri_kl?->id_kelas,
                'status'=>$ri_kl?->status
            ];
        }
        catch(\Exception $e)
        {
            $this->dispatch('swal:alert', title : "Galat ".$e->getCode(), text : $e->getMessage(), icon: "error");
        }
    }
    public function render()
    {
        return view('livewire.siswa.edit-form');
    }
}
