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
        Schema::create('guest', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->uuid("event_id", 50);
            $table->uuid("kategori_id", 50);
            $table->string("kode_token", 150)->unique()->nullable();
            $table->string("nama_tamu", 150);
            $table->string("keluarga", 150)->nullable();
            $table->integer("jumlah_undangan")->default(0);
            $table->enum("status_kehadiran", [1,0])->default(0);
            $table->foreign('event_id')->references('id')->on('event');
            $table->foreign('kategori_id')->references('id')->on('kategori');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest');
    }
};
