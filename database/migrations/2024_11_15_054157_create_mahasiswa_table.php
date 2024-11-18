<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->integer('nim')->primary();
            $table->unsignedBigInteger('dosen_id');
            $table->string(column: 'name');
            $table->unsignedInteger('prodi_id');
            $table->string('email')->unique();

            $table->foreign('prodi_id')->references('id')->on('program_studi')->onDelete('cascade');
            $table->foreign('dosen_id')->references('nip')->on('pembimbingakd')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
