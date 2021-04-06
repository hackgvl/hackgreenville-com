<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceDetailsToEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'events',
            function (Blueprint $table) {
                $table->string('service')->after('id')->default('hg');
                $table->string('service_id')->after('service')->default(1);
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'events',
            function (Blueprint $table) {
                $table->dropColumn('service', 'service_id');
            }
        );
    }
}
