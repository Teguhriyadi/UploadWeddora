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
        Schema::create('souvenir_deposit', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->uuid("guest_id", 50)->nullable();
            $table->string("nama_tamu", 150)->nullable();
            $table->string("nama_kado", 150);
            $table->integer("qty");
            $table->text("keterangan")->nullable();
            $table->string("foto")->nullable();
            $table->enum("status", ["DITITIPKAN", "SUDAH_DITERIMA_PENGANTIN"])->default("DITITIPKAN");
            $table->uuid("petugas_id", 50);
            $table->dateTime("waktu_dititipkan")->nullable();
            $table->dateTime("waktu_diterima_pengantin")->nullable();
            $table->foreign('petugas_id')->references('id')->on('users');
            $table->foreign('guest_id')->references('id')->on('guest');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('souvenir_deposit');
    }
};
