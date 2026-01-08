<!DOCTYPE html>
<html>
<head>
    <title>Laporan Mood Siswa</title>
    <style>
        @page { size: A4 landscape; margin: 20px; }
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 8px;
            background-color: #f4f4f4;
            padding: 5px;
            border-left: 4px solid #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .info-table td {
            border: none;
            padding: 4px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .emoji { font-family: 'Segoe UI Emoji', 'Noto Color Emoji', sans-serif; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Monitoring Kesehatan Mental Siswa</h2>
        <p>Depresiku - Sistem Monitoring Depresi Siswa</p>
    </div>

    <!-- Student Info -->
    <div style="float: left; width: 60%;">
        <div class="section-title" style="margin-top: 0;">Informasi Siswa</div>
        <table class="info-table">
            <tr><td width="120">Nama Lengkap</td><td width="10">:</td><td>{{ $siswa->nama_lengkap }}</td></tr>
            <tr><td>NISN</td><td>:</td><td>{{ $siswa->nisn }}</td></tr>
            <tr><td>Kelas</td><td>:</td><td>-</td></tr>
        </table>
    </div>
    <div style="float: right; width: 35%;">
        <div class="section-title" style="margin-top: 0;">Periode Laporan</div>
        <table class="info-table">
            <tr><td>Mulai</td><td>:</td><td>{{ \Carbon\Carbon::now()->subDays(13)->translatedFormat('d F Y') }}</td></tr>
            <tr><td>Sampai</td><td>:</td><td>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td></tr>
        </table>
    </div>
    <div style="clear: both;"></div>

    @if($dassScores)
    <div class="section-title">Hasil Asesmen DASS-21 Terakhir ({{ $dassScores['date'] }})</div>
    <table style="width: 50%;">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Skor</th>
                <th>Tingkat Keparahan</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Depresi</td><td>{{ $dassScores['depression'] }}</td><td>{{ $dassScores['depression_label'] }}</td></tr>
            <tr><td>Kecemasan</td><td>{{ $dassScores['anxiety'] }}</td><td>{{ $dassScores['anxiety_label'] }}</td></tr>
            <tr><td>Stres</td><td>{{ $dassScores['stress'] }}</td><td>{{ $dassScores['stress_label'] }}</td></tr>
        </tbody>
    </table>
    @endif

    @if(!empty($dassAnswers))
    <div class="section-title">Detail Jawaban DASS-21</div>
    <table>
        <thead>
            <tr>
                <th width="30">#</th>
                <th>Pertanyaan</th>
                <th width="80">Kategori</th>
                <th width="100">Jawaban</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dassAnswers as $ans)
            <tr>
                <td style="text-align: center;">{{ $ans['no'] }}</td>
                <td>{{ $ans['question'] }}</td>
                <td style="text-align: center;">{{ substr($ans['category'], 0, 1) }} ({{ $ans['category'] }})</td>
                <td>{{ $ans['answer_text'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="section-title">Riwayat Jurnal Harian & Mood (14 Hari Terakhir)</div>
    <table>
        <thead>
            <tr>
                <th width="70">Tanggal</th>
                <th width="40">Waktu</th>
                <th width="50">Status</th>
                <th width="100">Bagaimana Perasaan Hari Ini</th>
                <th width="80">Prediksi Kamera</th>
                <th width="80">Prediksi Teks</th>
                <th>Cerita Perasaan Hari Ini</th>
            </tr>
        </thead>
        <tbody>
            @foreach($moodHistory as $history)
            <tr>
                <td>{{ $history['tanggal'] }}</td>
                <td>{{ $history['waktu'] }}</td>
                <td>{{ $history['status'] }}</td>
                <td>{{ $history['manual_mood'] }}</td>
                <td>{{ $history['camera_emoji'] }} {{ $history['camera_pred'] }}</td>
                <td>{{ $history['text_pred'] }}</td>
                <td>{{ $history['catatan'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
    </div>
</body>
</html>
