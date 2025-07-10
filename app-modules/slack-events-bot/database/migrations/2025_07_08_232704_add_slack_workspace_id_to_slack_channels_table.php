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
        Schema::table('slack_channels', function (Blueprint $table) {
            $table->string('slack_workspace_id')->nullable()->after('channel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slack_channels', function (Blueprint $table) {
            $table->dropColumn('slack_workspace_id');
        });
    }
};
