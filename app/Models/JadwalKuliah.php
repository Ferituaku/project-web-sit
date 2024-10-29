<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalKuliah extends Model
{
    use HasFactory;
    protected $fillable = ['kodemk', 'hari', 'jam', 'nip', 'koderuang'];

    public function RuangKelas(): BelongsTo
    {
        return $this->belongsTo(RuangKelas::class, 'koderuang');
    }
    public function MataKuliah(): BelongsTo
    {
        return $this->belongsTo(Matakuliah::class, 'kodemk');
    }
    public function Pengampu(): BelongsTo
    {
        return $this->belongsTo(PembimbingAkd::class, 'nip', 'nip');
    }
}
