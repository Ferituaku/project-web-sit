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
        Schema::create('pembimbingakd', function (Blueprint $table) {
            $table->unsignedBigInteger('nip')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->boolean('dosen')->default(true);
            $table->boolean('dekan');
            $table->boolean(column: 'kaprodi');
            $table->boolean(column: 'dosen_wali');
            $table->integer('prodi_id')->unsigned();
            $table->foreign('prodi_id')->references('id')->on('program_studi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembimbingakd');
    }
};
