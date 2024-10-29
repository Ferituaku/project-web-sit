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
            $table->unsignedBigInteger('nim')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->bigInteger('nip')->unsigned();
        });

        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->foreign('nip')->references('nip')->on('pembimbing_akd')->onDelete('cascade');
        });

        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
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
