<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresensiLibur extends Model
{
    //
    protected $table='presensi_libur';
    protected $guarded=['id'];
    public $timestamps=false;
}
