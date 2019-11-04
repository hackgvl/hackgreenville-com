<?php


namespace App\Contracts;


abstract class CalendarContract
{
    public $calendar = null;
    public $events = [];

    abstract public function addEvent($start, $end, $title, $description, $allDay = false, $attributes);

    abstract public function getEvent();

    abstract public function js();

    abstract public function html();

    abstract public function initialize();
}
