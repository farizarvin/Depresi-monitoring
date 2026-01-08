<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KelasStoreRequest;
use App\Http\Requests\Admin\KelasUpdateRequest;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $jenjangFilter = $request->input('jenjang');
        $jurusanFilter = $request->input('jurusan');
        
        $query = Kelas::query();
        
        // Apply search
        if ($search) {
            $query->where('nama', 'LIKE', "%{$search}%");
        }
        
        // Apply jenjang filter
        if ($jenjangFilter) {
            $query->where('jenjang', $jenjangFilter);
        }
        
        // Apply jurusan filter
        if ($jurusanFilter) {
            $query->where('jurusan', $jurusanFilter);
        }
        
        $classes = $query->orderBy('jenjang', 'asc')
            ->orderBy('nama', 'asc')
            ->paginate(10)
            ->appends($request->except('page'));
        
        // Get distinct values for filters
        $jenjangs = Kelas::select('jenjang')->distinct()->orderBy('jenjang')->pluck('jenjang');
        $jurusans = Kelas::select('jurusan')->whereNotNull('jurusan')->distinct()->orderBy('jurusan')->pluck('jurusan');
        
        return view('admin.kelas.index', compact('classes', 'jenjangs', 'jurusans', 'search', 'jenjangFilter', 'jurusanFilter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KelasStoreRequest $request)
    {
        $classData=$request->validated();
        Kelas::create($classData);
        return redirect()->route('admin.kelas.index')
        ->with('success', [
            'icon'=>'success',
            'title'=>'Berhasil!',
            'text'=>'Kelas berhasil dibuat!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kelas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas)
    {
        if($kelas==null)
            return back()->with('status', 'Kelas tidak ditemukan!');
        return view('', compact('class_'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KelasUpdateRequest $request, Kelas $kelas)
    {
        if($kelas==null)
            return back()->with('error', 'Kelas tidak ditemukan!');
        $classData=$request->validated();
        $kelas->update($classData);
        return redirect()->route('admin.kelas.index')
        ->with('success', [
            'icon'=>'success',
            'title'=>'Berhasil!',
            'text'=>'Kelas berhasil diupdate!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kelas)
    {
        if($kelas==null)
            return back()->with('error', 'Kelas tidak ditemukan!');
        $kelas->delete();
        return redirect()->route('admin.kelas.index')
        ->with('success', [
            'icon'=>'success',
            'title'=>'Berhasil!',
            'text'=>'Kelas berhasil dihapus!'
        ]);
    }
}
