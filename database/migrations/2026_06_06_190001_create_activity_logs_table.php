<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('id', 50)->primary();
            $table->uuid('user_id', 50)->nullable()->index();
            $table->string('module', 120)->index();
            $table->string('action', 60)->index();
            $table->string('subject_type', 180)->nullable()->index();
            $table->string('subject_id', 80)->nullable()->index();
            $table->string('method', 10)->nullable();
            $table->string('url', 500)->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->longText('before')->nullable();
            $table->longText('after')->nullable();
            $table->longText('meta')->nullable();
            $table->timestamp('logged_at')->useCurrent();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

