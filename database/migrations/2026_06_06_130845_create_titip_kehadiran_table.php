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
        Schema::create('titip_kehadiran', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->uuid("wakil_id", 50)->nullable();
            $table->uuid("guest_id", 50)->nullable();
            $table->string("nama_tamu", 150)->nullable();
            $table->enum("alasan_tidak_hadir", ["Jarak Jauh", "Ada Keperluan", "Jadwal Padat", "Sedang Sakit", "Lainnya"])->default("Ada Keperluan");
            $table->text("catatan")->nullable();
            $table->dateTime("waktu_kehadiran");

            $table->foreign('guest_id')->references('id')->on('guest');
            $table->foreign('wakil_id')->references('id')->on('guest');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titip_kehadiran');
    }
};
