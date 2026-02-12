<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guilds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->default('⚔️');
            $table->text('description')->nullable();
            $table->string('invite_code', 8)->unique();
            $table->unsignedBigInteger('master_id');
            $table->timestamps();

            $table->foreign('master_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('guild_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guild_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('role', ['master', 'officer', 'member'])->default('member');
            $table->timestamp('joined_at')->useCurrent();

            $table->foreign('guild_id')->references('id')->on('guilds')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['guild_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guild_user');
        Schema::dropIfExists('guilds');
    }
};
