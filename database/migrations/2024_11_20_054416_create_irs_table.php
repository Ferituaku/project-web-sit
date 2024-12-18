<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('irs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nim');
            $table->integer('semester');
            $table->string('tahun_ajaran', 10);
            $table->integer('total_sks')->default(0);
            $table->enum('approval', ['0', '1', '2'])->default('0');
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
        });

        // Tabel pivot untuk relasi many-to-many antara IRS dan Jadwal Kuliah
        Schema::create('irs_jadwal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('irs_id');
            $table->unsignedBigInteger('jadwal_id');
            $table->timestamps();

            $table->foreign('irs_id')->references('id')->on('irs')->onDelete('cascade');
            $table->foreign('jadwal_id')->references('id')->on('jadwalKuliah')->onDelete('cascade');

            // Memastikan tidak ada duplikasi jadwal dalam satu IRS
            $table->unique(['irs_id', 'jadwal_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('irs_jadwal');
        Schema::dropIfExists('irs');
    }
};
