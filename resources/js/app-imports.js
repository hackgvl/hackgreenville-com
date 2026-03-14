// Importing third-party libraries
import * as Turbo from '@hotwired/turbo';
import Swal from 'sweetalert2';
import Alpine from 'alpinejs';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import listPlugin from '@fullcalendar/list';

window.FullCalendar = { Calendar };
window.FullCalendarPlugins = { dayGrid: dayGridPlugin, list: listPlugin };

try {
  window.Swal = Swal;
} catch (e) {
  // An error occurred while setting SweetAlert2 to the global scope
}

try {
  // Setting Alpine.js to the global scope and starting it.
  // Alpine.js is a lightweight JavaScript framework for composing behavior directly in HTML.
  window.Alpine = Alpine;
  Alpine.start();
} catch (e) {
  // An error occurred while setting Alpine.js to the global scope
}

/**
 * The code below is commented out but can be used to set up Laravel Echo and Pusher.
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.VITE_PUSHER_APP_KEY,
//     cluster: process.env.VITE_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
