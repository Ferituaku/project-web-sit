<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;
    protected $table = 'mahasiswa';


    // Fillable attributes
    protected $fillable = [
        'nim',
        'name',
        'email'
    ];

    // Relation to PembimbingAkd
    // public function pembimbingAkd()
    // {
    //     return $this->belongsTo(PembimbingAkd::class, 'nip', 'nip');
    // }
}
