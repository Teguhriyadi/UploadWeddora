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
        Schema::table('guest_public', function (Blueprint $table) {
            $table->enum("relasi", ["Saudara", "Teman Kerja", "Teman SMA", "Relasi Ortu", "Teman Kuliah", "Atasan"])->after("users_id");
            $table->enum("keterangan", ["CPP", "CPW"])->after("relasi");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guest_public', function (Blueprint $table) {
            //
        });
    }
};
