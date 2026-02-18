<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shop_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['avatar', 'title', 'consumable', 'cosmetic']);
            $table->unsignedInteger('price');
            $table->string('icon')->default('🛒');
            $table->string('image_url')->nullable();
            $table->string('rarity', 20)->default('common');
            $table->unsignedInteger('stock')->nullable(); // null = unlimited
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_items');
    }
};
