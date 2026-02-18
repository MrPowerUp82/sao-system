<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coin_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('amount'); // positive = earned, negative = spent
            $table->string('type', 30); // mission_reward, purchase, admin_grant, daily_login
            $table->string('description')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable(); // e.g. shop_item_id
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coin_transactions');
    }
};
