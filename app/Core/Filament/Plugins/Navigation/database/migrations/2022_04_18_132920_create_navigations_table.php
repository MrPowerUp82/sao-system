<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('user_id')->nullable();
            $table->ulid('tenant_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('handle')->unique();
            $table->json('items')->nullable();
            $table->boolean('is_active')->default(true)->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('navigations');
    }
};
