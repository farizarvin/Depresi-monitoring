<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class TahunAkademikStoreRequest extends FormRequest
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
        
        return [
            'status'=>'required|boolean',
            'tahun_mulai'=>'required|date_format:Y',
            'tahun_akhir'=>'required|date_format:Y|after:tahun_mulai',
            'tanggal_mulai'=>[
                'bail',
                'required',
                'date_format:Y-m-d',
                function($attr, $val, $fail) 
                {
                    if($this->tahun_mulai==null||$val==null) return;
                    $year1=$this->tahun_mulai;
                    $year2=Carbon::parse($val)->format('Y');

                    if($year1!=$year2)
                        $fail("$attr harus berada di tahun $year1");
                }
            ],
            'tanggal_selesai'=>[
                'bail',
                'required',
                'date_format:Y-m-d',
                function($attr, $val, $fail) 
                {
                    if($this->tahun_akhir==null||$val==null) return;
                    $year1=$this->tahun_akhir;
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
                    ($this['status'] ?? 0) == 0,
                    'not_in:1',
                    null
                ),
                Rule::unique('tahun_akademik', 'current')
                ->where('status', true)
                ->where('current', true)
            ],
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        // Tambahkan flash ke session
        session()->flash('error', [
            'icon'=>'error', 
            'title'=>'Galat 422',
            'text'=>'Validasi gagal, cek data yang anda inputkan!'
        ]);

        // Lanjutkan default behavior (redirect back dengan errors)
        throw (new ValidationException($validator))
                    ->errorBag($this->errorBag)
                    ->redirectTo($this->getRedirectUrl());
    }
}
