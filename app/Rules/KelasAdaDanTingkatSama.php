<?php

namespace App\Rules;

use App\Models\Kelas;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class KelasAdaDanTingkatSama implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $tingkat, $count;
    public function __construct($tingkat, $count)
    {
        $this->tingkat=$tingkat;
        $this->count=$count;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $total_kelas=Kelas::whereIn('id', $value)->where('tingkat', $this->tingkat)->count();
        if($this->count<>$total_kelas)
            $fail("Terdapat kelas yang salah tingkat");
        
    }
}
