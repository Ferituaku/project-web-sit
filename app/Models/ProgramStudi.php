<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $table = 'program_studi';
    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = ['id', 'nama'];

    public function Mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'prodi_id', 'id');
    }
    public function prodi()
    {
        return $this->hasMany(Matakuliah::class, 'prodi_id', 'id');
    }
}
