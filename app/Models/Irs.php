<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Irs extends Model
{
    use HasFactory;
    protected $table = 'irs';
    protected $fillable = [
        'nim',
        'jadwal_id',
    ];
    public function irs(): HasOne
    {
        return $this->hasOne(Mahasiswa::class, 'irs_id', 'id');
    }
    public function jadwal(): HasMany
    {
        return $this->hasMany(JadwalKuliah::class, 'jadwal_id', 'id');
    }
}
