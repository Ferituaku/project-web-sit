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
        Schema::create('irs_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('irs_id');
            $table->unsignedBigInteger('jadwal_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('irs_id')->references('id')->on('irs')->onDelete('cascade');
            $table->foreign('jadwal_id')->references('id')->on('jadwalKuliah')->onDelete('cascade');

            // Unique constraint for irs_id and jadwal_id
            $table->unique(['irs_id', 'jadwal_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('irs_detail');
    }
};
