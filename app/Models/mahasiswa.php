<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrManyThrough;

class Mahasiswa extends Model
{
    use HasFactory;
    protected $table = 'mahasiswa';


    // Fillable attributes
    protected $fillable = [
        'nim',
        'dosen_id',
        'name',
        'prodi_id',
        'email',
        'irs_id'
    ];

    // Relation to PembimbingAkd
    public function pembimbingAkd()
    {
        return $this->belongsTo(PembimbingAkd::class, 'dosen_id', 'nip');
    }
    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id', 'id');
    }
    // public function irs(): HasOne
    // {
    //     return $this->hasOne(Irs::class, 'irs_id', 'id');
    // }
    public function Irs(): HasOneOrManyThrough
    {
        return $this->hasOneThrough(JadwalKuliah::class, Irs::class);
    }
}
