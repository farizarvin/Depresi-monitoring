<?php

namespace App\Http\Controllers\Dashboard\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\Dass21Hasil;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GuruSiswaController extends Controller
{
    // DASS-21 Questions in Indonesian
    private const DASS21_QUESTIONS = [
        0 => ['text' => 'Saya merasa sulit untuk beristirahat', 'category' => 'Stress'],
        1 => ['text' => 'Saya menyadari mulut saya kering', 'category' => 'Anxiety'],
        2 => ['text' => 'Saya tidak dapat merasakan perasaan positif sama sekali', 'category' => 'Depression'],
        3 => ['text' => 'Saya mengalami kesulitan bernapas', 'category' => 'Anxiety'],
        4 => ['text' => 'Saya merasa sulit untuk memulai melakukan sesuatu', 'category' => 'Depression'],
        5 => ['text' => 'Saya cenderung bereaksi berlebihan terhadap situasi', 'category' => 'Stress'],
        6 => ['text' => 'Saya mengalami gemetar (misalnya di tangan)', 'category' => 'Anxiety'],
        7 => ['text' => 'Saya merasa menggunakan banyak energi untuk cemas', 'category' => 'Stress'],
        8 => ['text' => 'Saya khawatir akan situasi di mana saya mungkin panik', 'category' => 'Anxiety'],
        9 => ['text' => 'Saya merasa tidak ada hal yang bisa diharapkan', 'category' => 'Depression'],
        10 => ['text' => 'Saya merasa gelisah', 'category' => 'Stress'],
        11 => ['text' => 'Saya merasa sulit untuk bersantai', 'category' => 'Stress'],
        12 => ['text' => 'Saya merasa sedih dan tertekan', 'category' => 'Depression'],
        13 => ['text' => 'Saya tidak sabar dengan hal-hal yang menghalangi saya', 'category' => 'Stress'],
        14 => ['text' => 'Saya merasa hampir panik', 'category' => 'Anxiety'],
        15 => ['text' => 'Saya tidak bisa merasa antusias tentang apapun', 'category' => 'Depression'],
        16 => ['text' => 'Saya merasa tidak berharga sebagai manusia', 'category' => 'Depression'],
        17 => ['text' => 'Saya merasa mudah tersinggung', 'category' => 'Stress'],
        18 => ['text' => 'Saya menyadari detak jantung saya meski tidak melakukan aktivitas fisik', 'category' => 'Anxiety'],
        19 => ['text' => 'Saya merasa takut tanpa alasan yang jelas', 'category' => 'Anxiety'],
        20 => ['text' => 'Saya merasa hidup ini tidak bermakna', 'category' => 'Depression'],
    ];

    /**
     * Display list of students with latest mood (main page)
     */
    public function moodIndex()
    {
        $siswas = Siswa::with([
            // 'presensi' => function($query) {
            //     return $query->latest('waktu')->limit(1)->with('diary');
            // },
            'recaps' => function($query) {
                return $query->latest('created_at')->first();
            }
        ])
        ->where('need_selfcare', true)
        ->orWhere('is_depressed', true)
        ->get();
        

        $siswaData = $siswas->map(function($siswa) {
            $lastPresensi = $siswa->presensi()->with('diary');
            $lastPresensi = $siswa->recaps->count() > 0 ? $lastPresensi->where('waktu', '<=', $siswa->recaps->first()?->created_at) : $lastPresensi;
            $lastPresensi = $lastPresensi->latest('waktu')->first();
            $latestMood = '-';
            $latestMoodLabel = '-';
            
            if ($lastPresensi && $lastPresensi->diary && $lastPresensi->diary->swafoto_pred) {
                // try {
                //     $predJson = json_decode($lastPresensi->diary->swafoto_pred);
                //     if (isset($predJson->predicted)) {
                //         $latestMoodLabel = $predJson->predicted;
                //         $latestMood = $this->getEmoji($latestMoodLabel);
                //     }
                // } catch (\Exception $e) { }

                $latestMoodLabel=$lastPresensi->diary->swafoto_pred;
                
            }

            return [
                'id' => $siswa->id,
                'nama' => $siswa->nama_lengkap,
                'last_update' => $lastPresensi ? $lastPresensi->waktu->format('d M Y H:i') : '-',
                'mood_emoji' => $latestMoodLabel,
                'mood_label' => $latestMoodLabel
            ];
        });

        return view('guru.mood.index', compact('siswaData'));
    }

    /**
     * Display detailed mood report for a specific student
     */
    public function moodDetail($siswaId)
    {
        $siswa = Siswa::findOrFail($siswaId);
        
        // Get 14-day mood data
        $startDate = Carbon::now()->subDays(13);
        $endDate = Carbon::now();
        
        $presensiData = Presensi::where('id_siswa', $siswaId)
            ->whereDate('waktu', '>=', $startDate)
            ->whereDate('waktu', '<=', $endDate)
            ->with('diary')
            ->orderBy('waktu', 'desc')
            ->get();

        $moodHistory = $presensiData->map(function($presensi) {
            $emotionLabel = '-';
            $emotionEmoji = '-';
            $predictionJson = '-';
            
            if ($presensi->diary && $presensi->diary->swafoto_pred) {
                // $predictionJson = $presensi->diary->swafoto_pred;
                // try {
                //     $pred = json_decode($predictionJson);
                //     if (isset($pred->predicted)) {
                //         $emotionLabel = $pred->predicted;
                //         $emotionEmoji = $this->getEmoji($emotionLabel);
                //     }
                // } catch (\Exception $e) {}

                $emotionLabel=$presensi->diary->swafoto_pred;
            }

            return [
                'tanggal' => $presensi->waktu->format('d M Y'),
                'waktu' => $presensi->waktu->format('H:i'),
                'status' => $presensi->status,
                'emoji_manual' => $presensi->diary->emoji ?? '-',
                'emotion_label' => $emotionLabel,
                'emotion_emoji' => $emotionEmoji,
                'prediction_json' => $predictionJson,
                'catatan' => $presensi->diary->catatan ?? '-',
            ];
        });

        // Get latest DASS-21 result
        $dassResult = Dass21Hasil::where('id_siswa', $siswaId)
            ->latest()
            ->first();

        $dassScores = null;
        $dassAnswers = [];
        
        if ($dassResult) {
            $dassScores = $dassResult->calculateScores();
            $dassScores['depression_label'] = $this->getDepressionLabel($dassScores['depression']);
            $dassScores['anxiety_label'] = $this->getAnxietyLabel($dassScores['anxiety']);
            $dassScores['stress_label'] = $this->getStressLabel($dassScores['stress']);
            $dassScores['date'] = $dassResult->created_at->format('d M Y H:i');

            // Format answers with question text
            if (is_array($dassResult->answers)) {
                foreach ($dassResult->answers as $ans) {
                    $qIndex = is_array($ans) ? $ans['question_index'] : $ans->question_index;
                    $value = is_array($ans) ? $ans['value'] : $ans->value;
                    
                    $dassAnswers[] = [
                        'no' => $qIndex + 1,
                        'question' => self::DASS21_QUESTIONS[$qIndex]['text'] ?? "Pertanyaan $qIndex",
                        'category' => self::DASS21_QUESTIONS[$qIndex]['category'] ?? '-',
                        'answer' => $value,
                        'answer_text' => $this->getAnswerText($value),
                    ];
                }
                // Sort by question number
                usort($dassAnswers, fn($a, $b) => $a['no'] <=> $b['no']);
            }
        }

        // dd($moodHistory);
        return view('guru.mood.detail', compact('siswa', 'moodHistory', 'dassScores', 'dassAnswers'));
    }

    /**
     * Export mood data as CSV
     */
    public function exportMoodCsv($siswaId)
    {
        $siswa = Siswa::findOrFail($siswaId);
        
        $startDate = Carbon::now()->subDays(13);
        $endDate = Carbon::now();
        
        $presensiData = Presensi::where('id_siswa', $siswaId)
            ->whereDate('waktu', '>=', $startDate)
            ->whereDate('waktu', '<=', $endDate)
            ->with('diary')
            ->orderBy('waktu', 'desc')
            ->get();

        $dassResult = Dass21Hasil::where('id_siswa', $siswaId)->latest()->first();
        $dassScores = $dassResult ? $dassResult->calculateScores() : null;

        $filename = 'laporan_mood_' . str_replace(' ', '_', $siswa->nama_lengkap) . '_' . date('Ymd') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($presensiData, $dassScores, $siswa, $dassResult) {
            $file = fopen('php://output', 'w');
            
            // BOM for Excel UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Student Info
            fputcsv($file, ['Laporan Mood Siswa']);
            fputcsv($file, ['Nama', $siswa->nama_lengkap]);
            fputcsv($file, ['NISN', $siswa->nisn]);
            fputcsv($file, ['Periode', Carbon::now()->subDays(13)->format('d M Y') . ' - ' . Carbon::now()->format('d M Y')]);
            fputcsv($file, []);

            // DASS-21 Summary
            if ($dassScores) {
                fputcsv($file, ['Hasil DASS-21 (Tanggal: ' . $dassResult->waktu->format('d M Y') . ')']);
                fputcsv($file, ['Depression', $dassScores['depression'], $this->getDepressionLabel($dassScores['depression'])]);
                fputcsv($file, ['Anxiety', $dassScores['anxiety'], $this->getAnxietyLabel($dassScores['anxiety'])]);
                fputcsv($file, ['Stress', $dassScores['stress'], $this->getStressLabel($dassScores['stress'])]);
                fputcsv($file, []);

                // DASS-21 Answers Section
                fputcsv($file, ['Jawaban DASS-21']);
                fputcsv($file, ['No', 'Pertanyaan', 'Kategori', 'Skor', 'Jawaban']);
                
                if (is_array($dassResult->answers)) {
                    $answersData = [];
                    foreach ($dassResult->answers as $ans) {
                        $qIndex = is_array($ans) ? $ans['question_index'] : $ans->question_index;
                        $value = is_array($ans) ? $ans['value'] : $ans->value;
                        
                        $answersData[] = [
                            'no' => $qIndex + 1,
                            'question' => self::DASS21_QUESTIONS[$qIndex]['text'] ?? "Pertanyaan " . ($qIndex + 1),
                            'category' => self::DASS21_QUESTIONS[$qIndex]['category'] ?? '-',
                            'value' => $value,
                            'answer_text' => $this->getAnswerText($value),
                        ];
                    }
                    // Sort by question number
                    usort($answersData, fn($a, $b) => $a['no'] <=> $b['no']);
                    
                    foreach ($answersData as $ans) {
                        fputcsv($file, [
                            $ans['no'],
                            $ans['question'],
                            $ans['category'],
                            $ans['value'],
                            $ans['answer_text'],
                        ]);
                    }
                }
                fputcsv($file, []);
            }

            // Mood Data Header
            fputcsv($file, ['Riwayat Mood 14 Hari']);
            fputcsv($file, ['Tanggal', 'Waktu', 'Status Kehadiran', 'Prediksi Kamera', 'Catatan']);
            
            foreach ($presensiData as $p) {
                $emotionLabel = '-';
                if ($p->diary && $p->diary->swafoto_pred) {
                    try {
                        $pred = json_decode($p->diary->swafoto_pred);
                        $emotionLabel = $pred->predicted ?? '-';
                    } catch (\Exception $e) {}
                }

                fputcsv($file, [
                    $p->waktu->format('d M Y'),
                    $p->waktu->format('H:i'),
                    $p->status,
                    $emotionLabel,
                    $p->diary->catatan ?? '-',
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Placeholder page for nilai (grades)
     */
    public function nilaiIndex()
    {
        return view('guru.nilai.index');
    }

    // Helper methods
    private function getEmoji(string $label): string
    {
        return match(strtolower($label)) {
            'anger' => '😠',
            'disgust' => '🤢',
            'fear' => '😨',
            'sadness' => '😢',
            'surprise' => '😲',
            'happy' => '😊',
            default => '😐',
        };
    }

    private function getDepressionLabel(int $score): string
    {
        if ($score <= 9) return 'Normal';
        if ($score <= 13) return 'Ringan';
        if ($score <= 20) return 'Sedang';
        if ($score <= 27) return 'Parah';
        return 'Sangat Parah';
    }

    private function getAnxietyLabel(int $score): string
    {
        if ($score <= 7) return 'Normal';
        if ($score <= 9) return 'Ringan';
        if ($score <= 14) return 'Sedang';
        if ($score <= 19) return 'Parah';
        return 'Sangat Parah';
    }

    private function getStressLabel(int $score): string
    {
        if ($score <= 14) return 'Normal';
        if ($score <= 18) return 'Ringan';
        if ($score <= 25) return 'Sedang';
        if ($score <= 33) return 'Parah';
        return 'Sangat Parah';
    }

    private function getAnswerText(int $value): string
    {
        return match($value) {
            0 => 'Tidak pernah',
            1 => 'Kadang-kadang',
            2 => 'Sering',
            3 => 'Hampir selalu',
            default => '-',
        };
    }
}
