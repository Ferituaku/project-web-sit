<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RuangKelas extends Model
{
    use HasFactory;

    protected $table = 'ruangkelas';
    protected $primaryKey = 'koderuang';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['koderuang', 'kapasitas', 'approval'];

    public function jadwalKuliah(): HasMany
    {
        return $this->hasMany(JadwalKuliah::class, 'ruangkelas_id', 'koderuang');
    }
}
