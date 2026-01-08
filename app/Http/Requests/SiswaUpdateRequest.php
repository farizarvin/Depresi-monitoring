<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiswaUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id_siswa=$this->route('siswa')?->id;
        $id_user=$this->route('siswa')?->id_user;
        $current_date=now()->format('Y-m-d');
        
        return [
            "nama_lengkap"=>"required|max:50",
            "email"=>"required|email|unique:users,email,".$id_user,
            "nisn"=>"required|digits:10|unique:siswa,nisn,".$id_siswa.",id",
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
    }
}
