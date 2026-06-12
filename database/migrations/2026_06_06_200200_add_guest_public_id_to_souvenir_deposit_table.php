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
        Schema::table('souvenir_deposit', function (Blueprint $table) {
            $table->string('guest_public_id', 50)->nullable()->after('guest_id');
            $table->foreign('guest_public_id')->references('id')->on('guest_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('souvenir_deposit', function (Blueprint $table) {
            $table->dropForeign(['guest_public_id']);
            $table->dropColumn('guest_public_id');
        });
    }
};
