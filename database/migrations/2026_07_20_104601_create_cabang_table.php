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
        Schema::create('cabang', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->string("nama", 100);
            $table->string("kota", 100);
            $table->text("alamat")->nullable();
            $table->enum("status", ["AKTIF", "TIDAK AKTIF"])->default("AKTIF");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabang');
    }
};
