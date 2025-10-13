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
        Schema::dropIfExists('states');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('abbr', 10);
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
