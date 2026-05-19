<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi_toko', function (Blueprint $table) {

            $table->string('barcode', 8)->primary();

            $table->string('nama_toko', 50);

            $table->double('latitude')->nullable();

            $table->double('longitude')->nullable();

            $table->double('accuracy')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi_toko');
    }
};