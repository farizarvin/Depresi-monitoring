<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Siswa extends Model
{
    protected $table='siswa';
    protected $guarded=['id', 'created_at', 'updated_at'];
    // protected $with=['kelasAktif'];
    protected $primaryKey = 'id';
    public static function booted()
    {
        // static::deleted(function($siswa) {
        //     $img_url=$siswa->avatar_url ?? "";
        //     if(Storage::disk('private')->exists($img_url)==false) return;
        //     Storage::disk('private')->delete($img_url);
        // });
    }
    public function riwayat_kelas() : HasMany
    {
        return $this->hasMany(RiwayatKelas::class, 'id_siswa');
    }
    public function riwayat_kelas_aktif() : HasOne
    {
        return $this->hasOne(RiwayatKelas::class, 'id_siswa')->where('active', true);
    }
    public function classes() : BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'riwayat_kelas', 'id_siswa', 'id_kelas');
    }
    public function kelas_aktif() : BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'riwayat_kelas', 'id_siswa', 'id_kelas')->wherePivot("active", true)->limit(1);
    }
    public function getClassByAcademicYear($academiYearId)
    {
        return $this->riwayat_kelas()->where('id_thak', $academiYearId)?->first()?->kelas;
    }
    public function getKelasAktif()
    {
        return $this->kelas_aktif()?->first();
    }
    public function getActiveClass()
    {
        return $this->kelas_aktif()?->first();
    }
    public function classHistories()
    {
        return $this->hasMany(RiwayatKelas::class, 'id_siswa', 'id');
    }
    public function firstClassHistory()
    {
        return $this->hasOne(RiwayatKelas::class, 'id_siswa', 'id')->whereIn('status', ['NW', 'MM'])->orderBy('waktu', 'asc');
    }
    public function activeClass() 
    {
        return $this->classes()->where('status', true);
    }
    public function enrollYear()
    {
        return $this->belongsTo(TahunAkademik::class, 'id_thak_masuk', 'id');
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
    public function riwayatKelas() : HasMany
    {
        return $this->hasMany(RiwayatKelas::class, 'id_siswa');
    }
    public function attendances() : HasMany
    { 
        return $this->hasMany(Presensi::class, 'id_siswa', 'id');
    }
    public function recaps() : HasMany
    {
        return $this->hasMany(Dass21Hasil::class, 'id_siswa', 'id');
    }
    // public function classFilterIds($ids) : BelongsToMany
    // {
    //     return $this->kelas()->whereIn('id', $ids);
    // }

    public function scopeMatchClassHistory($query, $classes, $year, $grades)
    {
        return $query->whereHas('classHistories', function($query) use($classes, $year, $grades) {
            return $query->where('id_thak', $year)
                ->whereHas('class_', function($query) use($grades) {
                    return $query->whereIn('jenjang', $grades);
                })
                ->whereIn('id_kelas', $classes);
        });
    }
    public function scopeGetClass($query, $classes)
    {
        return $query->with(['user:id,avatar_url', 'classes'=>function($query) use($classes) {
            $query->whereIn('kelas.id', $classes)->where('status', '!=', 'CL');
        }]);
    }
    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'id_siswa');
    }

    public function kuesionerResults()
    {
        return $this->hasMany(Dass21Hasil::class, 'id_siswa');
    }
}
