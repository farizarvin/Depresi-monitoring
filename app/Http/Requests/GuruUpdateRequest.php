<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuruUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $guruId = $this->route('guru')?->id;
        
        return [
            'nip' => 'required|string|max:20|unique:guru,nip,' . $guruId,
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'gender' => 'required|in:0,1',
            'tgl_lahir' => 'required|date',
            'email' => 'required|email|unique:users,email,' . $this->input('id_user'),
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ];
    }

    public function messages(): array
    {
        return [
            'nip.required' => 'NIP wajib diisi',
            'nip.unique' => 'NIP sudah terdaftar',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'gender.required' => 'Gender wajib dipilih',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
        ];
    }
}
