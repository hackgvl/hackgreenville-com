/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './app-imports';

$('.loading').hide().removeClass('d-none');

window.showMoreTimeline = function (args) {
  Swal.fire(args);
};

// Initialize Aire form validation
document.addEventListener('DOMContentLoaded', function() {
  // Check if Aire is available
  if (typeof window.Aire !== 'undefined') {
    // Find all forms with Aire validation
    const forms = document.querySelectorAll('form[data-aire-component="form"]');
    
    forms.forEach(function(form) {
      // Initialize Aire validation for each form
      window.Aire.connect(form);
    });
  }
});
