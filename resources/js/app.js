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

// Enhanced Aire form validation with blur event and submit control
document.addEventListener('DOMContentLoaded', function () {
  // Check if Aire is available
  if (typeof window.Aire !== 'undefined') {
    // Find all forms with Aire validation
    const forms = document.querySelectorAll('form[data-aire-component="form"]');
    
    forms.forEach(function (form) {
      // Initialize Aire validation for each form
      window.Aire.connect(form);
      
      // Get all input fields in the form - select by data-aire attributes and standard inputs
      const inputs = form.querySelectorAll('input[name], textarea[name], select[name]');
      const submitButton = form.querySelector('[type="submit"]');
      const requiredFields = form.querySelectorAll('[required], input[name], textarea[name]');
      
      // Function to check if form is empty (for initial state)
      function isFormEmpty() {
        let isEmpty = false;
        requiredFields.forEach(field => {
          if (field.value.trim() === '') {
            isEmpty = true;
          }
        });
        return isEmpty;
      }
      
      // Function to check if form has errors
      function hasValidationErrors() {
        const errorContainers = form.querySelectorAll('[data-aire-component="errors"]');
        let hasErrors = false;
        
        errorContainers.forEach(container => {
          // Check if error container has visible errors
          if (container.children.length > 0 && !container.classList.contains('hidden') && container.style.display !== 'none') {
            hasErrors = true;
          }
        });
        
        // Also check for invalid fields
        const invalidFields = form.querySelectorAll('.border-red-500');
        if (invalidFields.length > 0) {
          hasErrors = true;
        }
        
        return hasErrors;
      }
      
      // Function to update submit button state
      function updateSubmitButton() {
        if (submitButton) {
          // Disable if form has errors OR if form is empty
          if (hasValidationErrors() || isFormEmpty()) {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            submitButton.classList.remove('hover:bg-blue-700');
          } else {
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            submitButton.classList.add('hover:bg-blue-700');
          }
        }
      }
      
      // Force validation trigger for all field types
      function triggerValidation(element) {
        // Skip if already validating to prevent infinite loops
        if (element.dataset.validating === 'true') {
          return;
        }
        
        // Mark as validating
        element.dataset.validating = 'true';
        
        // For Aire validation, we need to trigger both change and keyup events
        // Aire.js listens to: form.addEventListener('change', run, true) and form.addEventListener('keyup', run, true)
        const changeEvent = new Event('change', { bubbles: true, cancelable: true });
        const keyupEvent = new Event('keyup', { bubbles: true, cancelable: true });
        
        // Dispatch both events to ensure validation runs
        element.dispatchEvent(changeEvent);
        
        // Small delay before keyup to ensure proper event sequencing
        setTimeout(function() {
          element.dispatchEvent(keyupEvent);
        }, 10);
        
        // Clear validating flag after a brief delay
        setTimeout(function() {
          element.dataset.validating = 'false';
        }, 100);
      }
      
      // Add blur event listener to trigger validation
      inputs.forEach(input => {
        // Add focus event to prepare for validation
        input.addEventListener('focus', function(e) {
          // Mark that this field can be validated on blur
          e.target.setAttribute('data-can-validate', 'true');
        });
        
        // Mark field as touched and trigger validation on blur
        input.addEventListener('blur', function(e) {
          // Only validate if field has been focused at least once
          if (e.target.getAttribute('data-can-validate') === 'true') {
            e.target.setAttribute('data-touched', 'true');
            
            // Force validation by triggering events
            triggerValidation(e.target);
            
            // Update submit button state after a short delay to allow validation to complete
            setTimeout(updateSubmitButton, 200);
          }
        });
        
        // Also validate on input changes for touched fields
        input.addEventListener('input', function(e) {
          // Only validate if field has been touched
          if (e.target.getAttribute('data-touched') === 'true') {
            triggerValidation(e.target);
            setTimeout(updateSubmitButton, 200);
          }
        });
        
        // Additional change event listener for selects and radios
        input.addEventListener('change', function(e) {
          if (e.target.getAttribute('data-can-validate') === 'true') {
            e.target.setAttribute('data-touched', 'true');
            setTimeout(updateSubmitButton, 200);
          }
        });
      });
      
      // Prevent form submission if there are validation errors
      form.addEventListener('submit', function(e) {
        // First trigger validation on all fields
        inputs.forEach(input => {
          input.setAttribute('data-touched', 'true');
          triggerValidation(input);
        });
        
        // Check for errors after a brief delay
        setTimeout(function() {
          if (hasValidationErrors() || isFormEmpty()) {
            e.preventDefault();
            
            // Focus first invalid field
            const firstInvalid = form.querySelector('.border-red-500');
            if (firstInvalid) {
              firstInvalid.focus();
            } else {
              // If no red borders yet, focus first empty required field
              const firstEmpty = form.querySelector('[required]:invalid, [data-touched="true"]:invalid');
              if (firstEmpty) {
                firstEmpty.focus();
              }
            }
          }
        }, 100);
        
        if (hasValidationErrors() || isFormEmpty()) {
          e.preventDefault();
          return false;
        }
      });
      
      // Initial state: disable submit button if form is empty
      updateSubmitButton();
      
      // Also check after a delay to ensure Aire is fully initialized
      setTimeout(function() {
        // Trigger initial validation state check
        updateSubmitButton();
      }, 500);
      
      // Monitor for dynamic validation changes
      const observer = new MutationObserver(function(mutations) {
        updateSubmitButton();
      });
      
      // Observe changes to error containers
      const errorContainers = form.querySelectorAll('[data-aire-component="errors"]');
      errorContainers.forEach(container => {
        observer.observe(container, { 
          childList: true, 
          attributes: true, 
          attributeFilter: ['class', 'style'],
          subtree: true 
        });
      });
      
      // Also observe changes to input fields for class changes
      inputs.forEach(input => {
        observer.observe(input, {
          attributes: true,
          attributeFilter: ['class']
        });
      });
    });
  }
});
