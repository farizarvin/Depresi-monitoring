<?php

namespace App\Rules;

use App\Models\Angkatan;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\ValidationRule;

class ExistsAndActive implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        $data=Angkatan::find($value);
        if($data==null)
            $fail("Angkatan not found");
        $tahun=$data->tahun_mulai;
        if(in_array($tahun->status, ['ditutup', 'arsip']))
            $fail("Angkatan sudah ditutup");
    }
}
