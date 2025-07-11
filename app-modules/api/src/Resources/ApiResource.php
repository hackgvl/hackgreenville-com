<?php

namespace HackGreenville\Api\Resources;

use Str;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class ApiResource extends JsonResource {
    protected function getTime(): string {
        if ($this->isRunningScribe()) {
            return '2025-01-01T12:00:00.000000Z';
        }

        return now()->toISOString();
    }

    protected function getId($resource) {
        if ($this->isRunningScribe()) {
            return 123;
        }
        return $resource->id;
    }

    private function isRunningScribe() {
        $args=[];

        if (isset($_SERVER['argv'])) {
            $args = $_SERVER['argv'];
        }
        
        $is_running_in_console = app()->runningInConsole();
        $is_scribe_context = Str::contains(implode(' ', $args), 'scribe:generate');
        return $is_running_in_console && $is_scribe_context;
    }
}