<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diary;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAkademik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DiaryController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->input('search');
        $classFilter = $request->input('class');
        $academicYear = $request->has('year') ? $request->input('year') : TahunAkademik::orderByRaw('(3 - current - status) ASC')->orderBy('nama_tahun', 'desc')->first()?->id;

        $path = "data/config/konfigurasi_rekap_mental.json";
        if (!Storage::exists($path))
            throw new \Exception('File konfigurasi tidak ditemukan', 500);
        $config = Storage::get($path);
        $config = json_decode($config, true);
        $range = (int) $config['rentang'] ?? 1;
        $threshold = (int) $config['threshold'];

        // Build query
        $students = Siswa::query()
            ->with('activeClass', 'user')
            ->select(['id', 'nisn', 'nama_lengkap', 'id_user']);
        
        // Apply search filter
        if ($search) {
            $students->where(function($q) use($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('nisn', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply class filter
        if ($classFilter) {
            $students->whereHas('classes', function($query) use($classFilter) {
                $query->where('kelas.id', $classFilter);
            });
        }
        
        $students = $students->paginate(10)->appends($request->except('page'));
        $studentIds = $students->pluck('id')->toArray();
        

        // dd($students);
        $mentalHealthData = Diary::getMentalHealthData($academicYear, $studentIds, $range);
        $students = $students->through(function($student) use($mentalHealthData) {
            $details = collect($mentalHealthData->get($student->id));
            $dpMeter = $details->reduce(function($acc, $row) {
                $swafoto_pred = strtolower($row->swafoto_pred);
                $catatan_pred = strtolower($row->catatan_pred);
                $bool = ($catatan_pred === 'terindikasi depresi' && !in_array($swafoto_pred, ['happy', 'surprise']));
                return $acc + (int) $bool;
            }, 0);
            
            $percentage = 0;
            $totals = $details->count();
            
            $day1 = "";
            $day2 = "";
            if ($totals > 0)
            {
                $percentage = ($dpMeter / $totals) * 100;
                $day1 = $details->first()?->waktu;
                $day2 = $details->last()?->waktu;

                $day1 = Carbon::parse($day1)->format('j F Y');
                $day2 = Carbon::parse($day2)->format('j F Y');
            }

            $results = collect([
                'days' => $totals,
                'depression_rate' => $percentage,
                'time_range' => "$day1 - $day2"
            ]);

            $student->mental_health = collect([
                'result' => $results,
                'detail' => $details
            ]);
            return $student;
        });

        // Get filter options
        $classes = Kelas::select('id', 'nama')->orderBy('nama')->get();
        $academicYears = TahunAkademik::select('id', 'nama_tahun')->orderBy('nama_tahun', 'desc')->get();
        
        return view('admin.diary.index', compact('students', 'threshold', 'classes', 'academicYears', 'search', 'classFilter', 'academicYear'));
    }

    public function updateConfig(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'rentang'=>'required|integer|min:1'
        ]);
        if($validator->fails())
        {
            return back()->withError($validator->errors())
            ->withInput()
            ->with('error', [
                'icon'=>'error',
                'title'=>'Galat 404!',
                'text'=>'Input invalid'
            ]);
        }

        try
        {
            $path="data/config/konfigurasi_rekap_mental.json";
            if(!Storage::exists($path))
                throw new \Exception('File konfigurasi tidak ditemukan', 500);

            $config=Storage::get($path);
            $config=json_decode($config, true);
            $content=$validator->validated();
            $content=
            [
                ...$config,
                ...$content
            ];


            Storage::put($path, $content);
            return back()->with('success', [
                'icon'=>'success',
                'title'=>'Berhasil',
                'text'=>'Konfigurasi berhasil disimpan'
            ]);
        }
        catch(\Exception $e)
        {
            return back()
            ->with('error', [
                'icon'=>'error',
                'title'=>'Galat 500!',
                'text'=>$e->getMessage()
            ]);
        }
    }
}
