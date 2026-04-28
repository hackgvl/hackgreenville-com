<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('map_layers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('center_latitude', 10, 6)->default(34.850700);
            $table->decimal('center_longitude', 10, 6)->default(-82.398500);
            $table->unsignedTinyInteger('zoom_level')->default(10);
            $table->string('geojson_link')->nullable();
            $table->string('contribute_link')->nullable();
            $table->string('raw_data_link')->nullable();
            $table->json('maintainers')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_layers');
    }
};
