/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';

// load full calendar and register it to the window object
import FullCalendar from '@fullcalendar/core';
import FullCalendarDayGrid from '@fullcalendar/daygrid';

window.FullCalendar = FullCalendar;
window.FullCalendarDayGrid = FullCalendarDayGrid;

$('.loading').hide().removeClass('d-none');
