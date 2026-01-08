<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Kelas extends Model
{
    protected $table='kelas';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $fillable = ['nama', 'jurusan', 'jenjang', 'token'];
    
    public static function booted()
    {
        static::creating(function ($kelas) {
            $kelas->token = $kelas->token ?? substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);
        });
    }

    public $timestamps = false;
    public function riwayat_kelas() : HasMany
    {
        return $this->hasMany(RiwayatKelas::class, 'id_kelas');
    }
    public function siswa() : HasManyThrough
    {
        return $this->hasManyThrough(Siswa::class, RiwayatKelas::class, 'id_kelas', 'id_siswa');
    }
    
}
