<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PembimbingAkd extends Model
{
    use HasFactory;
    protected $table = 'pembimbingakd';

    protected $primaryKey = 'nip';

    protected $fillable = [
        'nip',
        'name',
        'email'
    ];
    public function pembimbingakd(): HasMany
    {
        return $this->hasMany(JadwalKuliah::class, 'dosen_id', 'nip');
    }
    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'nip', 'nip');
    // }
    // public function mahasiswa()
    // {
    //     return $this->hasMany(Mahasiswa::class, 'nip', 'nip');
    // }
}
