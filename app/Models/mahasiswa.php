<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // Fillable attributes
    protected $fillable = [
        'nim',
        'name',
        'email',
        'nip'
    ];

    // Relation to PembimbingAkd
    public function pembimbingAkd()
    {
        return $this->belongsTo(PembimbingAkd::class, 'nip', 'nip');
    }
}
