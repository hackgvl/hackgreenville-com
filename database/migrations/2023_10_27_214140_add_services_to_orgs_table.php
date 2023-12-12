<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orgs', function (Blueprint $table) {
            $table->string('service_api_key')->nullable()->after('event_calendar_uri');
            $table->string('service')->nullable()->after('event_calendar_uri');
        });
    }

    public function down(): void
    {
        Schema::table('orgs', function (Blueprint $table) {
            $table->dropColumn('service');
            $table->dropColumn('service_api_key');
        });
    }
};
