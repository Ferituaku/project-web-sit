<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwalKuliah', function (Blueprint $table) {
            $table->id();
            $table->string('ruangkelas_id');
            $table->unsignedInteger('kodemk');
            $table->string('dosen');
            $table->string('hari');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();

            $table->foreign('ruangkelas_id')->references('koderuang')->on('ruangkelas')->onDelete('cascade');
            $table->foreign('kodemk')->references('kodemk')->on('matakuliah')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kuliah');
    }
};
