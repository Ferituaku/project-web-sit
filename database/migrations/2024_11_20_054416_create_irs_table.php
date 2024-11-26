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
        Schema::create('irs', function (Blueprint $table) {
            $table->id()->primary();
            $table->unsignedBigInteger('nim');
            $table->integer('semester');
            $table->string('tahun_ajaran', 10);
            $table->enum('approval', ['0', '1', '2'])->default('0');
            $table->integer('total_sks')->default(0);

            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            // $table->foreign('semester')->references('semester')->on('mahasiswa')->onDelete('cascade');
            // $table->foreign('total_sks')->references('SKS')->on('mahasiswa')->onDelete('cascade');
            // $table->foreign('tahun_ajaran')->references('tahun_ajaran')->on('mahasiswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('irs');
    }
};
