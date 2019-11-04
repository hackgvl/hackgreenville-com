<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');

            $table->longText('event_name');
            $table->longText('group_name');
            $table->longText('description');

            $table->integer('rsvp_count')->nullable()->default(0);

            $table->dateTime('active_at')->nullable();
            $table->string('uri');

            $table->unsignedBigInteger('venue_id');

            $table->json('cache');

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
        Schema::dropIfExists('events');
    }
}
