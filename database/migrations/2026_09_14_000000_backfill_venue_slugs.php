<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        $venues = DB::table('venues')
            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->get(['id', 'name', 'slug']);

        foreach ($venues as $venue) {
            $slug = Str::slug((string) $venue->slug);

            if ($slug === '') {
                $slug = Str::slug($venue->name);
            }

            if ($slug === '' || $slug === $venue->slug) {
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
