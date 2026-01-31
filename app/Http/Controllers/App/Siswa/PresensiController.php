<?php

namespace App\Http\Controllers\App\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Dass21Hasil;
use App\Models\Diary;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\PresensiLibur;
use App\Models\RekapEmosi;
use App\Models\RiwayatKelas;
use App\Models\TahunAkademik;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator as ValidationValidator;

class PresensiController extends Controller
{
    protected $storage_path;
    // Middleware removed to allow route-level definition
    // public static function middleware()
    // {
    //     return ['auth:sanctum', 'role:siswa'];
    // }

    public function __construct()
    {
        $this->storage_path="data/config/konfigurasi_jadwal_harian.json";
    }
    private function getSisJjg()
    {
        $studentId=auth('web')->user()->siswa?->id;
        $riwayat = RiwayatKelas::where('id_siswa', $studentId)
            ->where('active', true)
            ->with('kelas')
            ->first();
            
        return $riwayat?->kelas?->jenjang;
    }
    private function cekLibur($date)
    {
        $jjg_kls=$this->getSisJjg();
        if (!$jjg_kls) return; // Skip if no class found

        $cur=Carbon::parse($date)->format('d-m');
        $lbr=PresensiLibur::select(['ket'])
            ->where('tanggal_mulai', '<=', $cur)
            ->where('tanggal_selesai', '>=', $cur)
            ->whereJsonContains('jenjang', $jjg_kls)
            ->first();
        if($lbr==null) return;
        throw ValidationException::withMessages(['tgl'=>'Libur, kegiatan kalender akademik']);
    }
    private function inTenggat($schedules, $dayOfWeek)  : bool
    {
        $jadwal= array_filter($schedules, function($index) use($dayOfWeek) {
            return $index==$dayOfWeek;
        }, ARRAY_FILTER_USE_KEY);
        if(count($jadwal)==0) return false;
        $jadwal = array_values($jadwal)[0]; // Reset keys
        $str=Carbon::createFromTimeString($jadwal['jam_mulai']);
        $end=Carbon::createFromTimeString($jadwal['jam_akhir']);
        return now()->between($str, $end, true);
    }
    private function cekPresensi($date, $grade)
    {
        $dayOfWeek=(int) Carbon::parse($date)->dayOfWeekIso - 1;
        if (!Storage::exists($this->storage_path)) 
            throw new \Exception('File konfigurasi jadwal tidak ditemukan di: ' . $this->storage_path);

        $jsonContent = Storage::get($this->storage_path);
        $config = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) 
            throw new \Exception('Format konfigurasi jadwal tidak valid: ' . json_last_error_msg());

        if (!$config) 
            throw new \Exception('Gagal membaca konfigurasi jadwal (Empty)');

        $config=$config[$grade];

        $weekdays=$config['hari_libur'] ?? [];
        $schedules=$config['jadwal'] ?? [];
        
        $is_weekday=in_array($dayOfWeek, $weekdays);
        if($is_weekday)
            throw ValidationException::withMessages(['tgl'=>'Hari libur']);

        $is_opened=$this->inTenggat($schedules, $dayOfWeek);
        if(!$is_opened)
            throw ValidationException::withMessages(['wkt'=>"Presensi sudah ditutup"]);

    }
    private function cekLimitPresensi($studentId, $grade)
    {
        $current = now();
        
        // Read limit from jadwal config
        if (!Storage::exists($this->storage_path)) 
            throw new \Exception('File konfigurasi jadwal tidak ditemukan');

        $config = json_decode(Storage::get($this->storage_path), true);
        $limit = (int) ($config[$grade]['limit_absen'] ?? 1);
        
        $attendances = Presensi::where('id_siswa', $studentId)
            ->whereDate('waktu', $current->format('Y-m-d'))
            ->count();
            
        if ($attendances >= $limit)
            throw ValidationException::withMessages([
                'attendance' => 'Anda sudah mencapai batas presensi hari ini'
            ]);
    }
    public function create()
    {
        try
        {
            $date=now()->format('Y-m-d');
            $this->cekPresensi($date, 1);
            $this->cekLibur($date);
            $response=
            [
                'msg'=>"Presensi untuk tanggal $date tersedia",
                'data'=>['is_open'=>true]
            ];
            return response()->json($response, 200);
        }
        catch(ValidationException $e)
        {
            $response=
            [
                'msg'=>"Presensi untuk tanggal $date tidak tersedia",
                'err'=>$e->getMessage(),
                'data'=>['is_open'=>true]
            ];
            return response()->json($response, 422);
        }
        catch(\Exception $e)
        {
            $response=
            [
                'msg'=>"Gagal mengecek jadwal",
                'err'=>$e->getMessage(),
                'data'=>['is_open'=>true]
            ];
            return response()->json($response, 500);
        }
    }
    private function getCurThak()
    {
        $year=TahunAkademik::select('id', 'nama_tahun')->where('current', true)->first();
        if($year==null) throw new \Exception('Tidak ada tahun akademik yang aktif', 404);
        return $year;
    }
    public function store(Request $request)
    {
        
        $validator=Validator::make($request->all(), [
            'swafoto'=>'required|image|mimes:jpg,png,jpeg|max:1024',
            'catatan'=>'required|max:255',
            'catatan_ket'=>'nullable|max:1000',
            'perasaan'=>'nullable|max:255',
            'status'=>'required|in:H,I,S,A',
            'ket'=>'required_if:status,I,S|max:255',
            'doc'=>'required_if:status,I,S|file|mimes:pdf,jpg,png,jpeg|max:10240',
        ]);
        if($validator->fails())
            return response()->json(['message' => $validator->errors()], 422);

        DB::beginTransaction();
        $data=$validator->validated();
        $student=$request->user()->siswa;
        try
        {
            // Read Config
            $path="data/config/konfigurasi_rekap_mental.json";
            if(!Storage::exists($path))
                throw new \Exception("File konfigurasi tidak ditemukan", 500);

            $config=Storage::get($path);
            $config=json_decode($config, true);

            // Check Time
            $date=now()->format('Y-m-d');
            $this->cekPresensi($date, $student->getActiveClass()?->jenjang);
            $this->cekLimitPresensi($student->id, $student->getActiveClass()?->jenjang);
            $this->cekLibur($date);
            
            // Set Variables
            $year=$this->getCurThak();
            [$selfiePath, $selfie]=$this->createSelfieFile($request, $student, $year);
            [$letterPath, $letter]=$this->createLetterFile($request, $student, $year);
            [$faceResult, $textResult]=$this->predictMental($selfie, $data['catatan']);
            if($faceResult['success']===false)
                throw new \Exception("Wajah tidak terdeteksi", 422);

            $data['catatan_pred']=$textResult['predicted'];
            $data['swafoto_pred']=$faceResult['predicted'];
            $attendance=$this->storeAttendance($data, $year->id, $student->id, null);
            $this->storeDiary($data, $attendance, $selfiePath);
            $this->setQuetionaryStatus($student, $year, $config);
            
            if($selfie!==null) Storage::disk('private')->put($selfiePath, $selfie);
            if($letter!==null) Storage::disk('private')->put($letterPath, $letter);

            DB::commit();
            $code=200;
            $response=
            [
                'message'=>'Berhasil presensi',
                'data'=>[],
                // Add prediction results for development debugging
                'debug' => [
                    'face_prediction' => $faceResult,
                    'text_prediction' => $textResult,
                ]
            ];
            return response()->json($response, $code);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            $code=$e->getCode()===0 ? 500 : $e->getCode();
            $response=
            [
                'message'=>$e->getMessage(),
                'data'=>[]
            ];
            return response()->json($response, $code);
        }
    }
    private function predictMental($selfie, $note)
    {
        $API_FACE_URL = config('prediction.face_url');
        $API_TEXT_URL = config('prediction.text_url');
        $faceRes=Http::attach('file', $selfie, 'selfie.jpg')->post($API_FACE_URL);
        $textRes=Http::post($API_TEXT_URL, ['text'=>$note]);

        $faceResult = $faceRes->json();
        $textResult = $textRes->json();

        // Log prediction results for development
        \Log::info('=== PREDICTION RESULTS ===');
        \Log::info('Face Prediction:', $faceResult);
        \Log::info('Text Prediction:', $textResult);
        \Log::info('==========================');

        return [$faceResult, $textResult];
    }
    private function createSelfieFile(Request $request, $student, $year)
    {
        $file=$request->file('swafoto');
        $directory='app/data/images/diaries/'.$student->nisn.'/'.$year->nama_tahun.'/';
        $filename='pres_'.$student->nisn.'_'.now()->format('dmYHi').'.'.$file->getClientOriginalExtension();
        $filepath=$directory.$filename;
        $file=file_get_contents($file);

        return [$filepath, $file];
    }
    private function createLetterFile(Request $request, $student, $year)
    {
        $filepath="";
        $file=null;
        if ($request->hasFile('doc')) {
            $file = $request->file('doc');
            $directory = '/data/docs/'.$student->nisn.'/'.$year->nama_tahun;
            $filename = 'doc_'.$student->nisn.'_'.now()->format('dmYHi').'.'.$file->getClientOriginalExtension();
            $filepath = $directory.'/'.$filename;
            $file=file_get_contents($file);
        }

        return [$filepath, $file];
    }
    private function storeAttendance($data, $yearId, $studentId, $letterFilePath)
    {
        $attendanceExcludes=['swafoto', 'catatan', 'doc', 'emoji', 'swafoto_pred', 'catatan_pred', 'catatan_ket'];
        $attendanceData=
        [
            ...array_diff_key($data, array_flip($attendanceExcludes)),
            'id_siswa'=>$studentId,
            'id_thak'=>$yearId,
            'doc'=>$letterFilePath
        ];
        $attendance=Presensi::create($attendanceData);
        return $attendance;
    }
    private function storeDiary($data, $attendance, $selfiePath)
    {
        $diaryExcludes=['swafoto', 'doc', 'ket', 'status'];
        $diaryData=
        [
            ...array_diff_key($data, array_flip($diaryExcludes)),
            'swafoto'=>$selfiePath,
            'id_presensi'=>$attendance->id,
            'catatan_ket'=>$data['catatan_ket'] ?? '-',
            'swafoto_pred'=>$data['swafoto_pred'] ?? '-',
            'catatan_pred'=>$data['catatan_pred'] ?? '-',
        ];
        $diary=Diary::create($diaryData);
        return $diary;
    }
    private function setQuetionaryStatus($student, $year, $config)
    {
        $yearId=$year->id;
        $lastRecap=Dass21Hasil::where('id_siswa', $student->id)->latest('created_at')->first();

        
        $range=(int) $config['rentang'];
        $threshold=(int) $config['threshold'];

        $subQuery = 
        $lastRecap===null? 
        function($query) use($student, $yearId) {
            return $query->where('id_thak', $yearId)->where('id_siswa', $student->id);
        }:
        function($query) use($student, $yearId, $lastRecap) {
            return $query->where('id_thak', $yearId)->where('id_siswa', $student->id)->where('waktu', $lastRecap->created_at);
        };

        $mentalData=Diary::orderBy('waktu', 'desc')->whereHas('attendance', $subQuery)->limit($range)->get();
        $depressionRate=$mentalData->reduce(function($acc, $row) {
            $swafoto_pred=strtolower($row->swafoto_pred);
            $catatan_pred=strtolower($row->catatan_pred);
            $bool=($catatan_pred==='terindikasi depresi' && !in_array($swafoto_pred, ['happy', 'surprise']));
            return $acc + (int) $bool;
        }, 0);

        $depressionRate=($depressionRate/$range)*100;
        $needSurvey=$depressionRate >= $threshold && $mentalData->count() >= $range;

        $student->need_survey=$needSurvey;
        if(!$needSurvey)
        {
            $student->is_depressed=false;
            $student->need_selfcare=false;
        }
        $student->save();
    }
}
