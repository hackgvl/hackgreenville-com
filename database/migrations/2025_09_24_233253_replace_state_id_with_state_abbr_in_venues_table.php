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
        DB::table('venues')->whereNotNull('state_id')->chunkById(100, function ($venues) {
            foreach ($venues as $venue) {
                $abbr = DB::table('states')->where('id', $venue->state_id)->value('abbr');
                if ($abbr) {
                    DB::table('venues')->where('id', $venue->id)->update(['state' => $abbr]);
                }
            }
        });

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
        DB::table('venues')->whereNotNull('state')->chunkById(100, function ($venues) {
            foreach ($venues as $venue) {
                $stateId = DB::table('states')->where('abbr', $venue->state)->value('id');
                if ($stateId) {
                    DB::table('venues')->where('id', $venue->id)->update(['state_id' => $stateId]);
                }
            }
        });

        // Drop the state column
        Schema::table('venues', function (Blueprint $table) {
            $table->dropColumn('state');
        });
    }
};
