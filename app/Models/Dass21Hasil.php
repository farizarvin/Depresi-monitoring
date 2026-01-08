<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dass21Hasil extends Model
{
    protected $table = 'dass21_hasils';
    protected $guarded = ['id'];

    protected $casts = [
        'answers' => 'array',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function calculateScores()
    {
        $depressionSum = 0;
        $anxietySum = 0;
        $stressSum = 0;

        if (is_array($this->answers)) {
            foreach ($this->answers as $ans) {
                // Handle different JSON structures if necessary (array vs object)
                $qIndex = is_array($ans) ? $ans['question_index'] : $ans->question_index;
                $val = is_array($ans) ? $ans['value'] : $ans->value;

                // Depression Indices
                if (in_array($qIndex, [2, 4, 9, 12, 15, 16, 20])) {
                    $depressionSum += $val;
                }
                // Anxiety Indices
                if (in_array($qIndex, [1, 3, 6, 8, 14, 18, 19])) {
                    $anxietySum += $val;
                }
                // Stress Indices
                if (in_array($qIndex, [0, 5, 7, 10, 11, 13, 17])) {
                    $stressSum += $val;
                }
            }
        }

        return [
            'depression' => $depressionSum * 2,
            'anxiety' => $anxietySum * 2,
            'stress' => $stressSum * 2,
            'total' => ($depressionSum + $anxietySum + $stressSum) * 2
        ];
    }
}
