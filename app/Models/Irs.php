<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Irs extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'irs';
    protected $guarded = ['id'];
    public $timestamps = true;
    // Define fillable attributes
    protected $fillable = [
        'nim',
        'semester',
        'tahun_ajaran',
        'total_sks',
        'approval',
        'total_sks',
    ];

    /**
     * Relationship to the Mahasiswa model (assuming a Mahasiswa has many IRS records)
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    /**
     * Relationship to the IrsDetail model
     */
    public function jadwalKuliah()
    {
        return $this->belongsToMany(JadwalKuliah::class, 'irs_jadwal', 'irs_id', 'jadwal_id');
    }
}
