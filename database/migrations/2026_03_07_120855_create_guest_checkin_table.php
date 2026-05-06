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
        Schema::create('guest_checkin', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->uuid("guest_id", 50);
            $table->enum("metode", ["qr", "manual"]);
            $table->dateTime("waktu_checkin");
            $table->uuid("users_id", 50);
            $table->foreign('guest_id')->references('id')->on('guest');
            $table->foreign('users_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_checkin');
    }
};
