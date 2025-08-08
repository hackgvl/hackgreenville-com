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

// Simplified Aire form validation - only manage submit button for working fields
document.addEventListener('DOMContentLoaded', function () {
  // Register nullable rule for Aire.js (for optional URL field)
  if (typeof window.Validator !== 'undefined') {
    window.Validator.register('nullable', function (val) {
      return true; // nullable fields always pass
    });
  }

  // Simple submit button management
  setTimeout(function () {
    const forms = document.querySelectorAll('form[data-aire-component="form"]');

    forms.forEach(function (form) {
      const submitButton = form.querySelector('[type="submit"]');
      if (!submitButton) return;

      // Only check name and email fields (these work with client validation)
      const nameField = form.querySelector('input[name="name"]');
      const emailField = form.querySelector('input[name="contact"]');
      const rulesCheckbox = form.querySelector('input[name="rules"]');

      function updateSubmitButton() {
        let canSubmit = true;

        // Check if name field is filled
        if (nameField && !nameField.value.trim()) {
          canSubmit = false;
        }

        // Check if email field is filled and valid
        if (emailField && !emailField.value.trim()) {
          canSubmit = false;
        }

        // Check if rules checkbox needs to be checked (sign-up form)
        if (rulesCheckbox && !rulesCheckbox.checked) {
          canSubmit = false;
        }

        // Enable/disable submit button
        if (canSubmit) {
          submitButton.disabled = false;
          submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
          submitButton.classList.add('hover:bg-blue-700');
        } else {
          submitButton.disabled = true;
          submitButton.classList.add('opacity-50', 'cursor-not-allowed');
          submitButton.classList.remove('hover:bg-blue-700');
        }
      }

      // Add event listeners to working fields only
      if (nameField) {
        nameField.addEventListener('input', updateSubmitButton);
        nameField.addEventListener('blur', updateSubmitButton);
      }

      if (emailField) {
        emailField.addEventListener('input', updateSubmitButton);
        emailField.addEventListener('blur', updateSubmitButton);
      }

      if (rulesCheckbox) {
        rulesCheckbox.addEventListener('change', updateSubmitButton);
      }

      // Initial state
      updateSubmitButton();
    });
  }, 100);
});
