<?php


namespace App\Contracts;


use Carbon\Carbon;

abstract class CalendarContract
{
    public $calendar = null;
    public $events = [];

    abstract public function addEvent(
            Carbon $start,
            Carbon $end = null,
            string $title,
            string $description,
            bool $allDay = false,
            array $attributes = []
    );

    abstract public function getEvents();

    abstract public function initialize();
}
