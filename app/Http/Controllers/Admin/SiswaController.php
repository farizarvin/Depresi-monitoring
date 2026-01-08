<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiswaStoreRequest;
use App\Http\Requests\SiswaUpdateRequest;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\RiwayatKelas;
use App\Models\TahunAkademik;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    private function generateProfileName($file)
    {
        $now=now()->format('dmYHis');
        return uniqid().$now.'.'.$file->getClientOriginalExtension();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $classFilter = $request->input('class');
        $statusFilter = $request->input('status');
        $genderFilter = $request->input('gender');
        
        $classes = Kelas::all();
        
        $query = Siswa::query()
            ->select(
                'siswa.*',
                'siswa.id as siswa.id_siswa',
                'users.email',
                'users.avatar_url',
                'rk.id_kelas',
                'rk.status as riwayat_status'
            )
            // Join users
            ->leftJoin('users', 'users.id', '=', 'siswa.id_user')
            // Join riwayat kelas terlama per siswa
            ->leftJoin(DB::raw('(
                SELECT id_siswa, id_kelas, status
                FROM (
                    SELECT 
                        id_siswa,
                        id_kelas,
                        status,
                        ROW_NUMBER() OVER (
                            PARTITION BY id_siswa 
                            ORDER BY waktu ASC
                        ) AS rn
                    FROM riwayat_kelas
                    WHERE status IN ("NW","MM")
                ) x
                WHERE rn = 1
            ) AS rk'), 'rk.id_siswa', '=', 'siswa.id');
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use($search) {
                $q->where('siswa.nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('siswa.nisn', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply class filter
        if ($classFilter) {
            $query->where('rk.id_kelas', $classFilter);
        }
        
        // Apply status filter
        if ($statusFilter !== null && $statusFilter !== '') {
            $query->where('siswa.status', $statusFilter);
        }

        // Apply gender filter
        if ($genderFilter !== null && $genderFilter !== '') {
            $query->where('siswa.gender', $genderFilter);
        }
        
        $students = $query->paginate(10)->appends($request->except('page'));
        
        $academicYears = TahunAkademik::where('status', true)->get()->sortBy(fn($item) => [1-$item->current, $item->nama_tahun]);
        
        return view('admin.siswa.index', compact('students', 'classes', 'academicYears', 'search', 'classFilter', 'statusFilter', 'genderFilter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes=Kelas::all();
        $academicYears=TahunAkademik::all();
        return view('', compact('classes', 'academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SiswaStoreRequest $request)
    {
        $validated=$request->validated();
        
        DB::beginTransaction();
        try 
        {
            // Set File Name
            
            $file=$request->file('avatar');
            $fileName=$file ? $this->generateProfileName($file) : "";

            // Create Data User
            $birthDate=Carbon::parse($validated['tanggal_lahir'])->format('dmY');
            $password='Nubi-'.$birthDate;
            $userData=[
                'email'=>$validated['email'],
                'username'=>$validated['nisn'],
                'password'=>$password,
                'avatar_url'=>$fileName,
                'role'=>'siswa'
            ];
            $user=User::create($userData);

            // Create Data Siswa
            $studentData=[
                ...array_diff_key($validated, array_flip(['email', 'avatar', 'id_kelas', 'status'])),
                'id_user'=>$user->id
            ];
            $student=Siswa::create($studentData);

            // Create Data Riwayat Kelas
            $classHistoryData=[
                'id_siswa'=>$student->id,
                'id_kelas'=>$validated['id_kelas'],
                'id_thak'=>$validated['id_thak_masuk'],
                'status'=>$validated['status'],
                'active'=>true
            ];
            RiwayatKelas::create($classHistoryData);

            // Store Avatar To Private Storage
            if($file)
            {
                $path="app/data/images/users/$user->id";
                $file->storeAs($path, $fileName, "private");
            }
            DB::commit();
            return redirect()->route('admin.siswa.index')
            ->with('success', [
                'icon'=>'success',
                'title'=>'Berhasil!',
                'text'=>'Siswa berhasil dibuat!'
            ]);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return redirect()->route('admin.siswa.index')
            ->with('success', [
                'icon'=>'error',
                'title'=>'Galat '.$e->getCode().'!',
                'text'=>$e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $student)
    {
        if(!$student)
            return back()->with('status', 'Siswa tidak ditemukan!');
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $student)
    {
        if(!$student)
            return back()->with('status', 'Siswa tidak ditemukan!');

        $classes=Kelas::all();
        $academicYears=TahunAkademik::all();
        return view('admin.siswa.index', compact('student', 'classes', 'academicYears'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SiswaUpdateRequest $request, Siswa $siswa)
    {
        $student=$siswa;
        if($student==null)
        {
            return redirect()->route('admin.siswa.index')
            ->with('error', [
                'icon'=>'error',
                'title'=>'Galat 404!',
                'text'=>'Siswa tidak ditemukan'
            ]);
        }

        $validated=$request->validated();

        DB::beginTransaction();
        try 
        {
            // Initialize related data
            $relatedUser=$student->user;
            $activeClass=$student->getActiveClass();
            $enrollYear=$student->enrollYear;
            

            // Set File Name
            $file=$request->file('avatar');
            $fileName=$file ? $this->generateProfileName($file) : $relatedUser->avatar_url;

            // Create Data User
            $birthDate=Carbon::parse($validated['tanggal_lahir'])->format('dmY');
            $password='Nubi-'.$birthDate;
            $userData=[
                'email'=>$validated['email'],
                'username'=>$validated['nisn'],
                'password'=>$password,
                'avatar_url'=>$fileName,
                'role'=>'siswa'
            ];
            $relatedUser->update($userData);

            // Create Data Siswa
            $studentData=array_diff_key($validated, array_flip(['email', 'avatar', 'id_kelas', 'status']));
            $student->update($studentData);

            
            // Create Data Riwayat Kelas
            $isHistoryChanged=($validated['id_kelas']!=$activeClass->id) || ($validated['id_thak_masuk']!=$enrollYear->id);
            if($isHistoryChanged)
            {
                $firstClassHistory=$student->firstClassHistory;
                
                $classHistoryData=[
                    'id_siswa'=>$student->id,
                    'id_kelas'=>$validated['id_kelas'],
                    'id_thak'=>$validated['id_thak_masuk'],
                    'status'=>$validated['status'],
                    'active'=>true
                ];
                $classHistory=RiwayatKelas::create($classHistoryData);
                $firstClassHistory->update(['status'=>'CL', 'active'=>false]);
            }

            // Store Avatar To Private Storage
            if($file)
            {
                $path="app/data/images/users/$relatedUser->id";
                $file->storeAs($path, $fileName, "private");
                
                $oldProfilePath=$path.$relatedUser->avatar_url;
                $oldProfileExists=Storage::disk('private')->exists($oldProfilePath);
                if($oldProfileExists)
                    Storage::disk('private')->delete($oldProfilePath);
            }
            DB::commit();
            return redirect()->route('admin.siswa.index')
            ->with('success', [
                'icon'=>'success',
                'title'=>'Berhasil!',
                'text'=>'Siswa berhasil diupdate'
            ]);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return redirect()->route('admin.siswa.index')
            ->with('success', [
                'icon'=>'error',
                'title'=>'Galat '.$e->getCode().'!',
                'text'=>$e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        if($siswa==null)
        {
            return redirect()->route('admin.siswa.index')
            ->with('error', [
                'icon'=>'error',
                'title'=>'Galat 404!',
                'text'=>'Siswa tidak ditemukan'
            ]);
        }

        $siswa->delete();
        return redirect()->route('admin.siswa.index')
        ->with('success', [
            'icon'=>'success',
            'title'=>'Berhasil!',
            'text'=>'Siswa berhasil dihapus'
        ]);
    }
}
