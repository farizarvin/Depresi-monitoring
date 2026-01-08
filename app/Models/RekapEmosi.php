<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapEmosi extends Model
{
    //
    protected $guarded = ['id'];
    protected $table='rekap_emosi';
    public $timestamps = false;
}
