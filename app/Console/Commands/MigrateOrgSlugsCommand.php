<?php

namespace App\Console\Commands;

use App\Models\Org;
use Glhd\ConveyorBelt\IteratesQuery;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MigrateOrgSlugsCommand extends Command
{
    use IteratesQuery;

    protected $signature = 'migrate:org-slugs';

    protected $description = 'Extracts slug from the URL. Should only be ran one time.';

    public function query()
    {
        return Org::query();
    }

    public function handleRow(Org $org)
    {
        $org->update([
            'slug' => Str::afterLast($org->path, '/'),
        ]);
    }
}
