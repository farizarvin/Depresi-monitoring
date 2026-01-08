<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Presensi;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->input('search');
        $classFilter = $request->input('class');
        $academicYear = $request->input('year') ?? TahunAkademik::orderByRaw('(3 - current - status) ASC')->orderBy('nama_tahun', 'desc')->first()?->id;
        
        // Build query for all active students
        $students = Siswa::query()
            ->where('status', 1) // Only active students
            ->with(['user:id,avatar_url', 'classes']);
        
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
        
        $students = $students->select(['id', 'nisn', 'nama_lengkap', 'id_user'])
            ->paginate(10)
            ->appends($request->except('page'));
        
        [$results, $details] = Presensi::getAttendanceCalc($academicYear, $students->pluck('id')->toArray());
        $studentAttendances = $students->through(function($student) use($results, $details) {
            $result = $results->get($student->id) ?? null;
            $detail = $details?->get($student->id) ?? null;

            $student->presensi = collect([
                'result' => $result,
                'details' => $detail
            ]);

            return $student;
        });
        
        // Get filter options
        $classes = Kelas::select('id', 'nama')->orderBy('nama')->get();
        $academicYears = TahunAkademik::select('id', 'nama_tahun')->orderBy('nama_tahun', 'desc')->get();
        
        return view('admin.presensi.index', compact('studentAttendances', 'classes', 'academicYears', 'search', 'classFilter', 'academicYear'));
    }
    public function show(Request $request, $student, $year)
    {
        $limit = 10;
        $page = $request->input('page') ?? 0;
        
        // Get student data with user/profile info
        $studentData = Siswa::with('user:id,avatar_url')
            ->select(['id', 'nisn', 'nama_lengkap', 'id_user'])
            ->find($student);
            
        if (!$studentData) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        
        // Get attendance history with full details
        $presensi = Presensi::where('id_siswa', $student)
            ->where('id_thak', $year)
            ->select(['id', 'status', 'waktu', 'ket', 'doc', 'id_siswa'])
            ->orderBy('waktu', 'desc')
            ->skip($page * $limit)
            ->limit($limit)
            ->get();
        
        // Get mental health data (diary entries with all fields)
        $diaryData = DB::table('diary')
            ->join('presensi', 'diary.id_presensi', '=', 'presensi.id')
            ->where('presensi.id_siswa', $student)
            ->where('presensi.id_thak', $year)
            ->select([
                'diary.id',
                'diary.waktu',
                'diary.catatan',           // Form perasaan
                'diary.catatan_ket',        // Ceritakan perasaan
                'diary.catatan_pred',       // AI prediction
                'diary.swafoto_pred',       // Selfie prediction
                'presensi.status',
                'presensi.waktu as presensi_waktu'
            ])
            ->orderBy('diary.waktu', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'response' => [
                'student' => $studentData,
                'attendances' => $presensi,
                'diary_entries' => $diaryData,
                'has_attendances' => $presensi->count() > 0,
                'has_diary' => $diaryData->count() > 0
            ]
        ], 200);
    }
}
