<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RuangKelas extends Model
{
    use HasFactory;

    protected $table = 'ruangKelas';
    protected $fillable = ['koderuang', 'kapasitas'];

    public function jadwalKuliah(): HasMany
    {
        return $this->hasMany(JadwalKuliah::class, 'koderuang');
    }
}
