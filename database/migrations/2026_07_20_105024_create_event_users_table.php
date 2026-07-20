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
        Schema::create('event_users', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->string("event_id", 50);

            $table->string("user_id", 50);
            $table->enum("jabatan", ["CUSTOMER", "PJ", "PETUGAS"]);

            $table->foreign('event_id')->references('id')->on('event');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_users');
    }
};
