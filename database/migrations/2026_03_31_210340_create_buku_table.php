<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->increments('idbuku'); // PK INT

            $table->string('kode')->unique();
            $table->string('judul');
            $table->string('pengarang');

            $table->unsignedInteger('idkategori');

            // Relasi ke kategori
            $table->foreign('idkategori')
                  ->references('idkategori')
                  ->on('kategori')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};