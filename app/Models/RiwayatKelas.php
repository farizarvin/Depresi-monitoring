<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatKelas extends Model
{
    protected $table='riwayat_kelas';
    protected $guarded=['id'];
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function kelas() : BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
    public function siswa() : BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
    public function class_() : BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id');
    }
}
