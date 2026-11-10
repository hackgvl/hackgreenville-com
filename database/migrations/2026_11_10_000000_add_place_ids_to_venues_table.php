<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->string('google_place_id', 255)->nullable()->index();
            $table->string('apple_maps_place_id', 255)->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->dropIndex(['google_place_id']);
            $table->dropIndex(['apple_maps_place_id']);
            $table->dropColumn(['google_place_id', 'apple_maps_place_id']);
        });
    }
};
