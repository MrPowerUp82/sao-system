<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('monsters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['common', 'intermediate', 'elite', 'master', 'boss']);
            $table->integer('floor_min');
            $table->integer('floor_max');
            $table->integer('specific_floor')->nullable();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('icon', 10)->default('👾');
            $table->integer('xp_reward')->default(100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monsters');
    }
};
