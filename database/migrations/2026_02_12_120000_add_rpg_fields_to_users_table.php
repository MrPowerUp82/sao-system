<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('level')->default(1)->after('password');
            $table->integer('xp')->default(0)->after('level');
            $table->string('player_name')->nullable()->after('xp');
            $table->string('avatar_url')->nullable()->after('player_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['level', 'xp', 'player_name', 'avatar_url']);
        });
    }
};
