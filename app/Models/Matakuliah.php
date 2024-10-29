<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matakuliah extends Model
{
    use HasFactory;
    protected $fillable = [
        'kodemk',
        'nama',
    ];

    public function JadwalKuliah(): HasMany
    {
        return $this->hasMany(JadwalKuliah::class, 'kodemk', 'kodemk');
    }
}
