<?php

namespace App\Rules;

use App\Models\TahunAjaran;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class SingleActive implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected string $table;
    protected string $col;
    protected $ignore_id;
    public function __construct(string $table, string $col, $ignore_id=null)
    {
        $this->table=$table;
        $this->col=$col;
        $this->ignore_id=$ignore_id;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $is_any_aktif=DB::table($this->table)
        ->where('id', '!=', $this->ignore_id)
        ->where($this->col, true)
        ->exists();
        if($value&&$is_any_aktif)
        {
            $fail("Sudah ada entri lain yang aktif");
        }
    }
}
