<?php

namespace App\Models\Siswa;

use Illuminate\Database\Eloquent\Model;

class Dash21 extends Model
{
    protected $table = 'dash21s';
    
    protected $fillable = [
        'id_siswa',
        'depression_score',
        'is_depressed'
    ];

    /**
     * Set logic for is_depressed based on score
     */
    public function setIsDepressed(int $score)
    {
        $this->depression_score = $score;
        // Logic: > 10 considered depressed (as per prompt example)
        $this->is_depressed = $score > 10;
        $this->save();
    }

    public function siswa()
    {
        return $this->belongsTo(\App\Models\Siswa::class, 'id_siswa');
    }
}
