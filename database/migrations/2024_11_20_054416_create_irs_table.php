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
            $table->id();
            $table->unsignedBigInteger('nim');
            $table->unsignedBigInteger('jadwal_id_1')->nullable();
            $table->unsignedBigInteger('jadwal_id_2')->nullable();
            $table->unsignedBigInteger('jadwal_id_3')->nullable();
            $table->unsignedBigInteger('jadwal_id_4')->nullable();
            $table->unsignedBigInteger('jadwal_id_5')->nullable();
            $table->unsignedBigInteger('jadwal_id_6')->nullable();
            $table->unsignedBigInteger('jadwal_id_7')->nullable();
            $table->unsignedBigInteger('jadwal_id_8')->nullable();

            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('jadwal_id_1')->references('id')->on('jadwalKuliah')->onDelete('cascade');
            $table->foreign('jadwal_id_2')->references('id')->on('jadwalKuliah')->onDelete('cascade');
            $table->foreign('jadwal_id_3')->references('id')->on('jadwalKuliah')->onDelete('cascade');
            $table->foreign('jadwal_id_4')->references('id')->on('jadwalKuliah')->onDelete('cascade');
            $table->foreign('jadwal_id_5')->references('id')->on('jadwalKuliah')->onDelete('cascade');
            $table->foreign('jadwal_id_6')->references('id')->on('jadwalKuliah')->onDelete('cascade');
            $table->foreign('jadwal_id_7')->references('id')->on('jadwalKuliah')->onDelete('cascade');
            $table->foreign('jadwal_id_8')->references('id')->on('jadwalKuliah')->onDelete('cascade');
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
