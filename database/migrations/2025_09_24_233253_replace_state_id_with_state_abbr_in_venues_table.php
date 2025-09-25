<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add the new state column
        Schema::table('venues', function (Blueprint $table) {
            $table->string('state', 2)->nullable()->after('city');
        });

        // Update existing data - convert state_id to state abbreviation
        DB::statement('
            UPDATE venues
            SET state = (
                SELECT abbr
                FROM states
                WHERE states.id = venues.state_id
            )
            WHERE state_id IS NOT NULL
        ');

        // Make state column not nullable after data migration
        Schema::table('venues', function (Blueprint $table) {
            $table->string('state', 2)->nullable(false)->change();
        });

        // Drop the old state_id column
        Schema::table('venues', function (Blueprint $table) {
            $table->dropColumn('state_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the state_id column
        Schema::table('venues', function (Blueprint $table) {
            $table->string('state_id')->nullable()->after('city');
        });

        // Convert state abbreviations back to state_id
        DB::statement('
            UPDATE venues
            SET state_id = (
                SELECT id
                FROM states
                WHERE states.abbr = venues.state
            )
            WHERE state IS NOT NULL
        ');

        // Drop the state column
        Schema::table('venues', function (Blueprint $table) {
            $table->dropColumn('state');
        });
    }
};
