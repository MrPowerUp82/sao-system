<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('financial_goals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->decimal('target_amount', 12, 2);
            $table->decimal('current_amount', 12, 2)->default(0);
            $table->integer('floor_number')->default(1);
            $table->string('icon')->default('🏰');
            $table->enum('status', ['locked', 'active', 'cleared'])->default('active');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_goals');
    }
};
