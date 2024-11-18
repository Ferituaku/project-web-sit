<?php
// First Migration - matakuliah
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matakuliah', function (Blueprint $table) {
            $table->integer('kodemk')->primary();
            $table->unsignedInteger('prodi_id');
            $table->string('nama_mk');
            $table->integer('sks');
            $table->integer('semester');
            $table->timestamps();

            $table->foreign('prodi_id')->references('id')->on('program_studi')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matakuliah');
    }
};
