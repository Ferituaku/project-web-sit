<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kaprodi extends Model
{
   protected $table = 'kaprodi';
   protected $primaryKey = 'nip';
   public $incrementing = false;
   protected $keyType = 'string';
   protected $fillable = ['nip', 'prodi_id'];

   public function pembimbingakd()
   {
       return $this->belongsTo(Pembimbingakd::class, 'nip', 'nip');
   }

   public function programStudi() 
   {
       return $this->belongsTo(ProgramStudi::class, 'prodi_id');
   }
}