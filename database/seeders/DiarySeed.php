<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Presensi;
use App\Models\Diary;

class DiarySeed extends Seeder
{
    public function run()
    {
        $presensis = Presensi::all();

        // Emoji mapping (1 = happy, 5 = anxiety)
        $emojiLabels = [
            1 => 'happy',
            2 => 'surprise',
            3 => 'disgust',
            4 => 'anger',
            5 => 'fear',
            6 => 'sadness'
        ];

        foreach ($presensis as $p) {

            // 1) Random emoji (1–5)
            $emoji = rand(1, 6);

            // 2) Swafoto pred (± 0–2 offset dari emoji, tapi tetap 1–5)
            $offset = rand(-2, 2);
            $swafotoPred = max(1, min(5, $emoji + $offset));

            // 3) Catatan_pred (indikasi mental)
            //    Happy/surprise → tidak terindikasi
            //    Sad/anger/anxiety → terindikasi
            $sentimentNegative = [3, 4, 5];

            $catatanPred =
                in_array($emoji, $sentimentNegative) || in_array($swafotoPred, $sentimentNegative)
                ? "Terindikasi depresi"
                : "Tidak terindikasi";

            // 4) catatan_ket
            $catatanKet = "-";

            // 5) catatan cerita pendek sesuai emoji
            $catatan = self::generateDiaryText($emojiLabels[$emoji]);

            Diary::create([
                'id_presensi'   => $p->id,
                'swafoto'       => '-',
                'swafoto_pred'  => $emojiLabels[$swafotoPred],
                'catatan_pred'  => $catatanPred,
                'catatan_ket'   => $catatanKet,
                'catatan'       => $catatan,
            ]);
        }
    }

    private static function generateDiaryText($emotion)
    {
        switch ($emotion) {

            case 'happy':
                return "Hari ini terasa ringan. Aku bisa fokus dan menikmati aktivitas tanpa beban berarti.";

            case 'surprise':
                return "Ada beberapa hal tak terduga yang terjadi, tapi rasanya cukup menyenangkan dan membuat hariku lebih hidup.";

            case 'sadness':
                return "Aku merasa sedikit down hari ini. Ada hal-hal kecil yang membuat pikiranku berat, tapi aku berusaha tetap jalan.";

            case 'anger':
                return "Hari ini terasa mengesalkan. Beberapa kejadian membuat emosi cepat naik, dan sulit menahan rasa jengkel.";

            case 'anxiety':
                return "Ada rasa cemas yang sulit dijelaskan. Pikiran terus bergerak, dan aku merasa tidak sepenuhnya tenang.";

            default:
                return "-";
        }
    }
}
