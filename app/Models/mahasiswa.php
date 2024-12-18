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
        'semester',
        'SKS',
        'tahun_ajaran',
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
    public function irs()
    {
        return $this->hasMany(Irs::class, 'nim', 'nim');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nim', 'nim');
    }
}
