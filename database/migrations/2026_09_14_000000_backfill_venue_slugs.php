<?php

use App\Models\Venue;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        $venues = DB::table('venues')
            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->get(['id', 'name', 'slug']);

        foreach ($venues as $venue) {
            $slug = Venue::normalizeSlug($venue->slug, $venue->name);

            if ($slug === null || $slug === $venue->slug) {
                continue;
            }

            DB::table('venues')->where('id', $venue->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        // Generated slugs are retained; they remain valid after rollback.
    }
};
