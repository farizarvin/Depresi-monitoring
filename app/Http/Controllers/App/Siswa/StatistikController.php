<?php

namespace App\Http\Controllers\App\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class StatistikController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;
        $id_siswa = $siswa->id;

        // Check today's attendance
        $todayPresensi = Presensi::where('id_siswa', $id_siswa)
            ->whereDate('waktu', now()->today())
            ->first();

        $isTodayPresent = $todayPresensi ? true : false;

        // Count history
        $history = Presensi::where('id_siswa', $id_siswa)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Default values
        $stats = [
            'H' => $history['H'] ?? 0,
            'I' => $history['I'] ?? 0,
            'S' => $history['S'] ?? 0,
            'A' => $history['A'] ?? 0,
        ];

        // Mood Chart Data (Last 14 Days)
        $endDate = now();
        $startDate = now()->subDays(13); // 14 days including today

        $moodData = Presensi::where('id_siswa', $id_siswa)
            ->whereDate('waktu', '>=', $startDate)
            ->whereDate('waktu', '<=', $endDate)
            ->with('diary')
            ->orderBy('waktu')
            ->get()
            ->map(function ($presensi) {
                $prediction = null;
                $moodVal = null;

                if ($presensi->diary && $presensi->diary->swafoto_pred) {
                    // swafoto_pred is already a string like 'happy', 'sadness', etc.
                    $prediction = strtolower($presensi->diary->swafoto_pred);
                }

                // Map prediction to 1-6 scale (same as admin panel)
                if ($prediction) {
                    switch($prediction) {
                        case 'sadness': $moodVal = 1; break;
                        case 'anger': $moodVal = 2; break;
                        case 'fear': $moodVal = 3; break;
                        case 'disgust': $moodVal = 4; break;
                        case 'happy': $moodVal = 5; break;
                        case 'surprise': $moodVal = 6; break;
                    }
                }

                return [
                    'date' => $presensi->waktu->format('d M'),
                    'emoji' => $moodVal,
                    'label' => $prediction
                ];
            });

        // Fill missing dates with null or previous value if needed (optional, but good for chart)
        // For now, let's just pass the data we have. Chart.js can handle gaps or we can fill them in JS.

        return view('siswa.statistik', compact('isTodayPresent', 'stats', 'moodData'));
    }
}
