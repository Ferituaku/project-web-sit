<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    protected $table = 'matakuliah';

    protected $primaryKey = 'kodemk';

    protected $fillable = [
        'kodemk',
        'nama_mk',
        'sks',
        'semester'
    ];

    public function jadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class, 'kodemk', 'kodemk');
    }
}
