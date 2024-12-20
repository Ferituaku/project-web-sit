<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalKuliah extends Model
{
    use HasFactory;
    protected $table = 'jadwalKuliah';

    protected $fillable = [
        'prodi_id',
        'ruangkelas_id',
        'kodemk',
        'dosen_id',
        'plot_semeter',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'approval',
    ];
    public function ruangKelas(): BelongsTo
    {
        return $this->belongsTo(RuangKelas::class, 'ruangkelas_id', 'koderuang');
    }
    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(Matakuliah::class, 'kodemk', 'kodemk');
    }
    public function pembimbingakd(): BelongsTo
    {
        return $this->belongsTo(PembimbingAkd::class, 'dosen_id', 'nip');
    }
    public function irs()
    {
        return $this->belongsToMany(Irs::class, 'irs_jadwal', 'jadwal_id', 'irs_id');
    }
    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id', 'id');
    }
}
