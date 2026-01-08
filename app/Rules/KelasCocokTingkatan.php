<?php

namespace App\Rules;

use App\Models\Kelas;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class KelasCocokTingkatan implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $tingkat;
    public function __construct($tingkat)
    {
        $this->tingkat=$tingkat;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $kelas=Kelas::find($value);
        if($kelas==null)
            $fail("Kelas tidak ditemukan");
        if($kelas->tingkat!=$this->tingkat)
            $fail("tingkat kelas tidak sesuai");
    }
}
