<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->increments('id_barang'); // PK INT
            $table->string('nama');
            $table->integer('harga'); // atau decimal kalau uang
            $table->timestamp('timestamp')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};