<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('generalized_transitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->unsignedFloat('total_value');
            $table->unsignedFloat('installment_value')->nullable();
            $table->unsignedInteger('installment_amount')->nullable();
            $table->text('description')->nullable();
            $table->char('type', length: 1)->comment("v = à vista\np = parcelado");
            $table->tinyInteger('input')->comment("1 = entrada\n0 = saída");
            $table->tinyInteger('fix')->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generalized_transitions');
    }
};
