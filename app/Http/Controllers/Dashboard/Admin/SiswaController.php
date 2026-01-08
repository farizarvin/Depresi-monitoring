<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatKelas;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Siswa;
use App\Models\User;
use App\Rules\ExistsAndActive;
use App\Rules\KelasCocokTingkatan;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class SiswaController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return ['auth'];
    }
    public function index()
    {
        $siswa=Siswa::where('status', true);
        // advanced filters
        $siswa=$siswa->get();
        return view('admin.siswa.index', compact('siswa'));
    }
    public function show($id)
    {
        $siswa=Siswa::find($id);
        if($siswa==null)
        {
            return redirect()
            ->back()
            ->with('error', 'Data siswa tidak ditemukan');
        }
        // return view
    }
    public function store(Request $request)
    {
        
        $current_date=now()->format('Y-m-d');
        $validator = Validator::make($request->all(), [
            "nama"=>"required|max:50",
            "alamat"=>"required|max:255",
            "gender"=>"required|boolean",
            "tanggal_lahir"=>"required|date_format:Y-m-d|before:$current_date",
            "tanggal_masuk"=>"required|date_format:Y-m-d|before_or_equal:$current_date",
            "avatar"=>"nullable|image|mimes:jpg,png,jpeg|max:500",
            "tingkat"=>"required|integer|between:1,3",
            "nisn"=>"required|unique:siswas|digits:10",
            "email"=>"required|email|unique:siswas",
            'no_telp'=>'required|unique:siswas|regex:/(08)[0-9]{9,11}/',
            "id_kelas"=>["required_if:tingkat,1,2,3", new KelasCocokTingkatan($request->tingkat)],
            "id_angkatan"=>["required", new ExistsAndActive()]
        ]);

        if($validator->fails())
        {
            return redirect()
            ->back()
            ->with('error', 'Data siswa tidak ditemukan');
        }

        DB::beginTransaction();
        try
        {
            $data=$validator->validated();
            // Store avatar
            $storage_path=null;
            $file=$request->file('avatar');
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
            return;
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return;
        }
        
    }
    public function update(Request $request, Siswa $siswa)
    {
        $user = $siswa->user;
        if($siswa==null)
        {
            return;
        }

        $id=$siswa->id;
        $current_date=now()->format('Y-m-d');
        $validator = Validator::make($request->all(), [
            "nama"=>"required|max:50",
            "alamat"=>"required|max:255",
            "gender"=>"required|boolean",
            "tanggal_lahir"=>"date|date_format:Y-m-d|before:$current_date",
            "tanggal_masuk"=>"required|date_format:Y-m-d|before_or_equal:$current_date",
            "avatar"=>"nullable|image|mimes:jpg,png,jpeg|max:500",
            "tingkat"=>"required|integer|between:1,3",
            "nisn"=>"required|unique:siswas,nisn,$id,nisn|digits:10",
            "email"=>"required|email|unique:siswas,email,$id,nisn",
            "no_telp"=>"required|unique:siswas,no_telp,$id,nisn|regex:/(08)[0-9]{9,11}/",
            "id_kelas"=>["required_if:tingkat,1,2,3", new KelasCocokTingkatan($request->tingkat)],
            "id_angkatan"=>["required", new ExistsAndActive()]
        ]);

        if($validator->fails())
        {
            return;
        }
        

        DB::beginTransaction();
        $data=$validator->validated();
        try
        {
            $storage_path=null;
            $file=$request->file('avatar');
            if($file!=null)
            {
                $filename=uniqid().'_'.now()->format('dmY').$file->getClientOriginalExtension();
                $old_path=$user->avatar_url;
                $storage_path='/data/images/avatars/'.$filename;
                Storage::disk('private')->delete($old_path);
                Storage::disk('private')->put($storage_path, file_get_contents($file));
            }
            if($user->username==$siswa->nisn && $siswa->nisn!=$data['nisn'])
            {
                $data_user_baru=['username'=>$data['nisn']];
                $user->update($data_user_baru);
            }
            $siswa->update($data);
            
            DB::commit();
            return;
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return;
        }
    }
    public function destroy(Siswa $siswa)
    {
        if($siswa==null)
        {
            return;
        }
        $siswa->delete();
        return;
    }
}
