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
        Schema::create('lp_tema', function (Blueprint $table) {
            $table->uuid("id", 50)->primary();
            $table->string("nama", 150);
            $table->string("subtitle", 200);
            $table->text("deskripsi");
            $table->string("badge", 100);
            $table->string("lp_kategori_id", 50);
            $table->foreign('lp_kategori_id')->references('id')->on('lp_kategori');
            $table->string("img_bg")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lp_tema');
    }
};
