<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->enum('slot', ['weapon', 'armor', 'accessory', 'consumable', 'material']);
            // weapon = cartão de crédito, armor = seguro, accessory = conta bancária,
            // consumable = assinatura, material = investimento
            $table->string('rarity', 20)->default('common');
            // common, uncommon, rare, epic, legendary
            $table->decimal('value', 12, 2)->default(0);
            $table->string('icon')->default('📦');
            $table->text('description')->nullable();
            $table->json('attributes')->nullable(); // {"limit": 5000, "bank": "Nubank", etc}
            $table->boolean('equipped')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
