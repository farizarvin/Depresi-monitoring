<?php

namespace App\Http\Controllers\App\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Dass21Hasil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Dass21Controller extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'answers' => 'required|json',
        ]);

        $answers = json_decode($request->answers, true);
        
        // Calculate scores based on DASS-21 Logic
        // Depression: 3, 5, 10, 13, 16, 17, 21 (Indices: 2, 4, 9, 12, 15, 16, 20)
        // Anxiety: 2, 4, 7, 9, 15, 19, 20 (Indices: 1, 3, 6, 8, 14, 18, 19)
        // Stress: 1, 6, 8, 11, 12, 14, 18 (Indices: 0, 5, 7, 10, 11, 13, 17)
        // Note: Indices in array are 0-based, Question numbers are 1-based.

        // Initialize sum
        $depressionSum = 0;
        $anxietySum = 0;
        $stressSum = 0;

        foreach ($answers as $ans) {
            $qIndex = $ans['question_index']; // 0-20
            $val = $ans['value'];      // 0-3

            // Classification Logic
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

        // Multiply by 2 for full DASS-42 equivalent
        $finalDepression = $depressionSum * 2;
        $finalAnxiety = $anxietySum * 2;
        $finalStress = $stressSum * 2;
        $totalScore = $finalDepression + $finalAnxiety + $finalStress;

        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return response()->json(['message' => 'Siswa not found'], 404);
        }

        $result = Dass21Hasil::create([
            'id_siswa' => $siswa->id,
            'answers' => $answers,
            'total_score' => $totalScore
        ]);

        $need_selfcare=($finalDepression <= 9) && ($finalAnxiety <= 7) && ($finalStress <= 14);
        $siswa->need_survey=false;
        $siswa->need_selfcare=$need_selfcare;
        $siswa->is_depressed=!$need_selfcare;
        $siswa->save();

        return response()->json([
            'message' => 'Sukses!', 
            'redirect' => route('siswa.diaryku'),
            'scores' => [
                'depression' => $finalDepression,
                'anxiety' => $finalAnxiety,
                'stress' => $finalStress,
                'total' => $totalScore
            ]
        ]);
    }

    public function diarykuDashboard()
    {
        $siswa = Auth::user()->siswa;
        
        $latestResult = null;
        $scores = null;

        if ($siswa) {
            $latestResult = $siswa->kuesionerResults()->latest()->first();
            if ($latestResult) {
                // Use the model method we just added
                $scores = $latestResult->calculateScores();
            }

            // dd($latestResult);
        }

        return view('siswa.selfcare', compact('latestResult', 'scores'));
    }
}
