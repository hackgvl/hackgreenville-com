<?php

namespace App\Console\Commands;

use App\Models\MapLayer;
use App\Services\MapLayerSyncService;
use Illuminate\Console\Command;

class SyncMapLayersCommand extends Command
{
    protected $signature = 'map:sync {--slug= : Sync a specific map layer by slug}';

    protected $description = 'Sync map layer GeoJSON files from their remote data sources';

    public function handle(MapLayerSyncService $service): int
    {
        if ($slug = $this->option('slug')) {
            return $this->syncOne($service, $slug);
        }

        return $this->syncAll($service);
    }

    private function syncOne(MapLayerSyncService $service, string $slug): int
    {
        $layer = MapLayer::where('slug', $slug)->first();

        if ( ! $layer) {
            $this->error("Map layer '{$slug}' not found.");

            return self::FAILURE;
        }

        $this->info("Syncing {$layer->title}...");
        $result = $service->sync($layer);

        if ($result['success']) {
            $this->info("  ✓ {$result['message']}");

            return self::SUCCESS;
        }

        $this->error("  ✗ {$result['message']}");

        return self::FAILURE;
    }

    private function syncAll(MapLayerSyncService $service): int
    {
        $results = $service->syncAll();
        $failed = 0;

        foreach ($results as $slug => $result) {
            if ($result['success']) {
                $this->info("  ✓ {$slug}: {$result['message']}");
            } else {
                $this->error("  ✗ {$slug}: {$result['message']}");
                $failed++;
            }
        }

        $total = count($results);
        $this->newLine();
        $this->info("Done. " . ($total - $failed) . "/{$total} synced successfully.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
