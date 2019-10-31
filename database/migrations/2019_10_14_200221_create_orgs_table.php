<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orgs', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('title');
            $table->string('path')->nullable();
            $table->string('city')->nullable();
            $table->string('focus_area')->nullable();
            $table->string('uri')->nullable();
            $table->string('primary_contact_person')->nullable();
            $table->string('organization_type')->nullable();
            $table->string('event_calendar_uri')->nullable();

            $table->longText('cache');

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
        Schema::dropIfExists('orgs');
    }
}
