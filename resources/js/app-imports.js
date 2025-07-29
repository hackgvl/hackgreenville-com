// Importing third-party libraries
import Lodash from 'lodash';
import moment from 'moment';
import Swal from 'sweetalert2';
import jquery from 'jquery';

// Adding Lodash to the global scope so it can be used anywhere in the application
window._ = Lodash;

try {
  // Setting jQuery to the global scope.
  // jQuery is still used in some parts of the application
  window.$ = window.jQuery = jquery;
} catch (e) {
  // An error occurred while setting jQuery to the global scope
}

try {
  // Setting Moment.js to the global scope.
  // Moment.js is a JavaScript library to parse, validate, manipulate and display dates and times.
  window.moment = moment;
} catch (e) {
  // An error occurred while setting Moment.js to the global scope
}

try {
  // Setting SweetAlert2 to the global scope.
  // SweetAlert2 is a library to create beautiful, responsive, customizable and accessible alert messages.
  window.Swal = Swal;
} catch (e) {
  // An error occurred while setting SweetAlert2 to the global scope
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
