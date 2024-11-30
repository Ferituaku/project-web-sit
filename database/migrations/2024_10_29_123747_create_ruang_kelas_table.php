<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ruangkelas', function (Blueprint $table) {
            $table->string('koderuang')->primary();
            $table->integer('kapasitas');
            $table->unsignedInteger('program_studi_id')->nullable();
            $table->timestamps();

            $table->foreign('program_studi_id')->references('id')->on('program_studi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruangkelas');
    }
};
