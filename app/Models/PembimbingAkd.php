<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembimbingAkd extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'name',
        'email'
    ];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'nip', 'nip');
    }
}
