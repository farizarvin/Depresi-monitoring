<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    public $table = "guru";

    protected $fillable = [
        'id_user',
        'nip',
        'nama_lengkap',
        'alamat',
        'gender',
        'tgl_lahir',
        'tgl_lahir',
        'id_kelas',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}
