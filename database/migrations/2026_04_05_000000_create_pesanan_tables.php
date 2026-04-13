<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * CATATAN: User sudah memiliki database dengan struktur berikut.
     * Migration ini hanya untuk reference jika ingin membuat ulang.
     */
    public function up(): void
    {
        // Tabel Vendor sudah ada
        if (!Schema::hasTable('vendor')) {
            Schema::create('vendor', function (Blueprint $table) {
                $table->increments('idvendor');
                $table->string('nama_vendor', 255);
            });
        }

        // Tabel Menu sudah ada
        if (!Schema::hasTable('menu')) {
            Schema::create('menu', function (Blueprint $table) {
                $table->increments('idmenu');
                $table->string('nama_menu', 255);
                $table->integer('harga');
                $table->string('path_gambar', 255)->nullable();
                $table->unsignedInteger('idvendor')->nullable();
                $table->foreign('idvendor')
                    ->references('idvendor')
                    ->on('vendor')
                    ->onDelete('cascade');
            });
        }

        // Tabel Pesanan sudah ada
        if (!Schema::hasTable('pesanan')) {
            Schema::create('pesanan', function (Blueprint $table) {
                $table->increments('idpesanan');
                $table->string('nama', 255);
                $table->timestamp('timestamp')->useCurrent();
                $table->integer('total');
                $table->string('metode_bayar', 50)->nullable()->comment('VA atau QRIS');
                $table->smallInteger('status_bayar')->default(0)->comment('0: Belum Bayar, 1: Lunas');
                $table->string('transaction_id', 255)->nullable();
                $table->string('snap_token', 255)->nullable();
                $table->string('status_message', 255)->nullable();
            });
        }

        // Tabel Detail Pesanan sudah ada
        if (!Schema::hasTable('detail_pesanan')) {
            Schema::create('detail_pesanan', function (Blueprint $table) {
                $table->increments('iddetail_pesanan');
                $table->unsignedInteger('idmenu')->nullable();
                $table->unsignedInteger('idpesanan')->nullable();
                $table->integer('jumlah');
                $table->integer('harga')->comment('Harga saat dipesan');
                $table->integer('subtotal');
                $table->timestamp('timestamp')->useCurrent();
                $table->string('catatan', 255)->nullable();
                $table->foreign('idmenu')
                    ->references('idmenu')
                    ->on('menu');
                $table->foreign('idpesanan')
                    ->references('idpesanan')
                    ->on('pesanan')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('menu');
        Schema::dropIfExists('vendor');
    }
};
