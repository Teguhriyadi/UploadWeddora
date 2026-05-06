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
        Schema::create('guest_public', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->string("nama");
            $table->string("nomor_handphone", 50)->nullable();
            $table->string("pekerjaan", 150)->nullable();
            $table->text("alamat")->nullable();
            $table->dateTime("waktu_checkin");
            $table->uuid("users_id", 50);
            $table->foreign('users_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_public');
    }
};
