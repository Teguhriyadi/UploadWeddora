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
        Schema::table('titip_kehadiran', function (Blueprint $table) {
            $table->string('wakil_guest_public_id', 50)->nullable()->after('wakil_id');
            $table->foreign('wakil_guest_public_id')->references('id')->on('guest_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('titip_kehadiran', function (Blueprint $table) {
            $table->dropForeign(['wakil_guest_public_id']);
            $table->dropColumn('wakil_guest_public_id');
        });
    }
};
