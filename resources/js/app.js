/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';

$('.loading').hide().removeClass('d-none');

window.showMoreTimeline = function (event) {
  Swal.fire({
    title: event.title,
    html: event.html,
  });
};
