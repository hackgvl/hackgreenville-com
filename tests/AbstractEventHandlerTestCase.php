<?php

namespace Tests;

use App\Enums\EventServices;

abstract class AbstractEventHandlerTestCase extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $eventService = $this->getEventService();
        $handlerClass = $this->getHandlerClass();

        // Set the handler mapping
        config([
            'event-import-handlers.handlers.' . $eventService->value => $handlerClass,
        ]);

        // Set the active service if not already set
        $activeServices = config('event-import-handlers.active_services', []);
        if ( ! in_array($eventService->value, $activeServices)) {
            $activeServices[] = $eventService->value;
            config(['event-import-handlers.active_services' => $activeServices]);
        }
    }

    /**
     * Get the event service enum for this test class
     */
    abstract protected function getEventService(): EventServices;

    /**
     * Get the handler class for this test
     */
    abstract protected function getHandlerClass(): string;
}
