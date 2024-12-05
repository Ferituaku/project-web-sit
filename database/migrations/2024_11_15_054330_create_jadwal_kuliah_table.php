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
            $table->unsignedInteger('prodi_id');
            $table->string('ruangkelas_id');
            $table->integer('kodemk');
            $table->unsignedBigInteger('dosen_id');
            $table->integer('plot_semester');
            $table->enum('class_group', ['A', 'B', 'C']); // Added class group field
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('approval', ['0', '1', '2'])->default('0');
            $table->timestamps();

            $table->foreign('prodi_id')->references('id')->on('program_studi')->onDelete('cascade');
            $table->foreign('dosen_id')->references('nip')->on('pembimbingakd')->onDelete('cascade');
            $table->foreign('ruangkelas_id')->references('koderuang')->on('ruangkelas')->onDelete('cascade');
            $table->foreign('kodemk')->references('kodemk')->on('matakuliah')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwalKuliah');
    }
};
