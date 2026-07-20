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
        Schema::table('event', function (Blueprint $table) {
            $table->string('cabang_id', 50)->nullable()->after('id');
            $table->string("nama_cpp", 150)->nullable()->after("cabang_id");
            $table->string("nama_cpw", 150)->nullable()->after("nama_cpp");
            $table->string("slug", 150)->after("nama_event")->nullable();
            $table->enum("status", ["DRAFT", "AKTIF", "SELESAI"])->after("lokasi")->default("DRAFT");

            $table->foreign('cabang_id')->references('id')->on('cabang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event', function (Blueprint $table) {
            //
        });
    }
};
