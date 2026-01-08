<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuruStoreRequest;
use App\Http\Requests\GuruUpdateRequest;
use App\Models\Guru;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    private function generateProfileName($file)
    {
        $now = now()->format('dmYHis');
        return uniqid() . $now . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $genderFilter = $request->input('gender');

        $query = Guru::query()
            ->select('guru.*', 'users.email', 'users.avatar_url')
            ->leftJoin('users', 'users.id', '=', 'guru.id_user');

        // Apply search filter
        if ($search) {
            $query->where(function($q) use($search) {
                $q->where('guru.nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('guru.nip', 'LIKE', "%{$search}%");
            });
        }

        // Apply gender filter
        if ($genderFilter !== null && $genderFilter !== '') {
            $query->where('guru.gender', $genderFilter);
        }

        $teachers = $query->paginate(10)->appends($request->except('page'));

        return view('admin.guru.index', compact('teachers', 'search', 'genderFilter'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GuruStoreRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Set File Name
            $file = $request->file('avatar');
            $fileName = $file ? $this->generateProfileName($file) : "";

            // Create Data User
            $birthDate = Carbon::parse($validated['tgl_lahir'])->format('dmY');
            $password = 'Guru_' . $birthDate;
            $userData = [
                'email' => $validated['email'],
                'username' => $validated['nip'],
                'password' => $password,
                'avatar_url' => $fileName,
                'role' => 'guru'
            ];
            $user = User::create($userData);

            // Create Data Guru
            $teacherData = [
                ...array_diff_key($validated, array_flip(['email', 'avatar'])),
                'id_user' => $user->id
            ];
            Guru::create($teacherData);

            // Store Avatar To Private Storage
            if ($file) {
                $path = "app/data/images/users/$user->id";
                $file->storeAs($path, $fileName, "private");
            }

            DB::commit();
            return redirect()->route('admin.guru.index')
                ->with('success', [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Guru berhasil ditambahkan!'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.guru.index')
                ->with('error', [
                    'icon' => 'error',
                    'title' => 'Galat ' . $e->getCode() . '!',
                    'text' => $e->getMessage()
                ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GuruUpdateRequest $request, Guru $guru)
    {
        if ($guru == null) {
            return redirect()->route('admin.guru.index')
                ->with('error', [
                    'icon' => 'error',
                    'title' => 'Galat 404!',
                    'text' => 'Guru tidak ditemukan'
                ]);
        }

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Initialize related data
            $relatedUser = $guru->user;

            // Set File Name
            $file = $request->file('avatar');
            $fileName = $file ? $this->generateProfileName($file) : ($relatedUser->avatar_url ?? '');

            // Update Data User
            $birthDate = Carbon::parse($validated['tgl_lahir'])->format('dmY');
            $password = 'Guru_' . $birthDate;
            $userData = [
                'email' => $validated['email'],
                'username' => $validated['nip'],
                'password' => bcrypt($password),
                'avatar_url' => $fileName,
                'role' => 'guru'
            ];
            $relatedUser->update($userData);

            // Update Data Guru
            $teacherData = array_diff_key($validated, array_flip(['email', 'avatar']));
            $guru->update($teacherData);

            // Store Avatar To Private Storage
            if ($file) {
                $path = "app/data/images/users/$relatedUser->id";
                $file->storeAs($path, $fileName, "private");

                $oldProfilePath = $path . $relatedUser->avatar_url;
                $oldProfileExists = Storage::disk('private')->exists($oldProfilePath);
                if ($oldProfileExists) {
                    Storage::disk('private')->delete($oldProfilePath);
                }
            }

            DB::commit();
            return redirect()->route('admin.guru.index')
                ->with('success', [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Guru berhasil diupdate'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.guru.index')
                ->with('error', [
                    'icon' => 'error',
                    'title' => 'Galat ' . $e->getCode() . '!',
                    'text' => $e->getMessage()
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $guru)
    {
        if ($guru == null) {
            return redirect()->route('admin.guru.index')
                ->with('error', [
                    'icon' => 'error',
                    'title' => 'Galat 404!',
                    'text' => 'Guru tidak ditemukan'
                ]);
        }

        DB::beginTransaction();
        try {
            // Delete related user (will cascade delete guru due to foreign key)
            if ($guru->user) {
                $guru->user->delete();
            } else {
                $guru->delete();
            }

            DB::commit();
            return redirect()->route('admin.guru.index')
                ->with('success', [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Guru berhasil dihapus'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.guru.index')
                ->with('error', [
                    'icon' => 'error',
                    'title' => 'Galat ' . $e->getCode() . '!',
                    'text' => $e->getMessage()
                ]);
        }
    }
}
