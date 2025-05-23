<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slack_cooldowns', function (Blueprint $table) {
            $table->id();
            $table->string('accessor')->comment('Unique identifier from whomever is accessing the resource');
            $table->string('resource')->comment('Unique identifier for whatever is rate-limited');
            $table->dateTime('expires_at')->comment('When the accessor will be allowed to access the resource again');
            $table->timestamps();

            $table->unique(['accessor', 'resource']);
            $table->index(['accessor', 'resource']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slack_cooldowns');
    }
};
