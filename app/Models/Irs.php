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

    // Define fillable attributes
    protected $fillable = [
        'nim',
        'semester',
        'tahun_ajaran',
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
    public function details(): HasMany
    {
        return $this->hasMany(IrsDetail::class, 'irs_id', 'id');
    }
}
