<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('slack_messages', function (Blueprint $table) {
            $table->id();
            $table->dateTime('week');
            $table->string('message_timestamp');
            $table->text('message');
            $table->integer('sequence_position')->default(0);
            $table->foreignId('channel_id')->constrained('slack_channels')->onDelete('cascade');
            $table->timestamps();

            $table->index('week');
            $table->index(['channel_id', 'week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slack_messages');
    }
};
