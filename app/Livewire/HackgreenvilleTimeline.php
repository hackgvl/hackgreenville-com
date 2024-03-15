<?php

namespace App\Livewire;

use App\Models\Event;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class HackgreenvilleTimeline extends Component
{
    public $title;
    public $loading = true;
    public $events = [];

    public function mount($title): void
    {
        $this->title = $title;

        $this->loadEvents();
    }

    public function showMore($eventId): void
    {
        $event = $this->events->firstWhere('id', $eventId);

        if ($event) {
            $this->dispatch(
                'show-alert',
                title: $event['title'],
                desc: $event['description'], // Add any additional data you need
                uri: $event['uri'],
                cancelled: $event['cancelled'],
            );
        }
    }

    public function loadEvents(): void
    {
        $events = Event::getActive()
            ->take(5)
            ->select(['id', 'event_name', 'group_name', 'description', 'active_at', 'expire_at', 'uri', 'cancelled_at'])
            ->get();

        $this->events = $events;
        $this->loading = false;
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('livewire.hackgreenville-timeline');
    }
}
