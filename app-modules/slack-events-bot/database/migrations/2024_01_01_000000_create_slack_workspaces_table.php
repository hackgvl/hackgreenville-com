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
        Schema::create('slack_workspaces', function (Blueprint $table) {
            $table->id();
            $table->string('team_id')->unique();
            $table->string('team_name');
            $table->text('access_token');
            $table->string('bot_user_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slack_workspaces');
    }
};
