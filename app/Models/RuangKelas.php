<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RuangKelas extends Model
{
    use HasFactory;

    protected $table = 'ruangkelas';
    protected $primaryKey = 'koderuang';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['koderuang', 'kapasitas', 'program_studi_id'];


    public function jadwalKuliah(): HasMany
    {
        return $this->hasMany(JadwalKuliah::class, 'ruangkelas_id', 'koderuang');
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id', 'id');
    }
}
