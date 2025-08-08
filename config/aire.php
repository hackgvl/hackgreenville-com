<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Grouping Behavior
    |--------------------------------------------------------------------------
    |
    | Elements that can be grouped (like <input> tags) are grouped by default.
    | You can disable this on an element-by-element basis by using the
    | `withoutGroup()` method, but if you would like to turn grouping off
    | by default, you can set this configuration value.
    |
    */
    'group_by_default' => true,

    /*
    |--------------------------------------------------------------------------
    | Automatically generate input IDs
    |--------------------------------------------------------------------------
    |
    | If an input does not have an "id" attribute set, Aire can automatically
    | create one. This improves UX by ensuring that <label> tags are always
    | associated with the correct tag.
    |
    */
    'auto_id' => true,

    /*
    |--------------------------------------------------------------------------
    | Default to Verbose Summaries
    |--------------------------------------------------------------------------
    |
    | By default, the Summary element will only display a message about the
    | number of errors that need to be resolved. If you would like, you can
    | change the default behavior to also include an enumerated list of the
    | errors in the summary box.
    |
    */
    'verbose_summaries_by_default' => false,

    /*
    |--------------------------------------------------------------------------
    | Default Client-Side Validation
    |--------------------------------------------------------------------------
    |
    | Aire comes with built-in client-side validation. By default, it is
    | enabled when available. You can disable this on a form-by-form basis
    | by using the `withoutValidation()` method, but if you would like to turn
    | off validation by default, you can set this configuration value.
    |
    */
    'validate_by_default' => true,

    /*
    |--------------------------------------------------------------------------
    | Client-Side Validation Scripts
    |--------------------------------------------------------------------------
    |
    | For easiest integration, Aire will inline the javascript necessary to
    | perform client-side validation. You can instead publish the JS scripts
    | and load them via `<script>` tags to take advantage of HTTP caching.
    |
    */
    'inline_validation' => true,
    'validation_script_path' => env('APP_URL') . '/vendor/aire/js/aire.js',

    /*
    |--------------------------------------------------------------------------
    | Default Attributes
    |--------------------------------------------------------------------------
    |
    | If you would like to configure default attributes for certain elements,
    | you can do so here (for example, setting a <form>'s method to 'GET' by
    | default).
    |
    */
    'default_attributes' => [
        'form' => [
            'method' => 'POST',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Classes
    |--------------------------------------------------------------------------
    |
    | If you would like to configure default CSS class names for certain elements,
    | you can do so here (for example, changing all <input> elements to have
    | the class .form-control for Bootstrap compatibility).
    |
    | These should be in the format '[element]' => '[class names]'
    | e.g. 'checkbox_label' => 'font-bold'
    |
    | See default-theme.php for a full example of configuring class names.
    |
    */
    'default_classes' => [
        // Form inputs
        'input' => 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary',
        'textarea' => 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary resize-vertical',
        'select' => 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary',

        // Labels
        'label' => 'block text-sm font-medium text-gray-700 mb-2',

        // Checkbox specific
        'checkbox' => 'mr-2 mt-1 rounded border-gray-300 text-primary focus:ring-primary',
        'checkbox_label' => 'inline-flex items-start text-gray-700',

        // Groups
        'group' => 'mb-6',

        // Buttons
        'submit' => 'bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors cursor-pointer',
        'button' => 'bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors cursor-pointer',

        // Error messages
        'error' => 'mt-1 text-sm text-red-600',
        'errors' => 'mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded',
        'group_errors' => 'mt-2 mb-3', // Error list container (removed hidden class)

        // Summary
        'summary' => 'mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded',
    ],

    /*
    |--------------------------------------------------------------------------
    | Variant Classes
    |--------------------------------------------------------------------------
    |
    | Some themes may define variants, such as "sm" or "lg" or "primary".
    | If you need to override any of these, do so here.
    |
    */
    'variant_classes' => [],

    /*
    |--------------------------------------------------------------------------
    | Validation Classes
    |--------------------------------------------------------------------------
    |
    | A grouped element can optionally have a validation state set. This can
    | be not validated, invalid, or valid. You can configure these class names
    | on an element-by-element basis here.
    |
    | These should be in the format '[element]_[sub element]' => '[class names]'
    | e.g. 'checkbox_label' => 'font-bold'
    |
    | See default-theme.php for a full example of configuring class names.
    |
    */
    'validation_classes' => [

        /*
        |--------------------------------------------------------------------------
        | Not Validated
        |--------------------------------------------------------------------------
        |
        | These classes will be applied to elements that have not been validated.
        |
        | These should be in the format '[element]' => '[class names]'
        | e.g. 'checkbox_label' => 'font-bold'
        |
        | See default-theme.php for a full example of configuring class names.
        |
        */
        'none' => [
            'input' => 'border-gray-300',
            'textarea' => 'border-gray-300', 
            'select' => 'border-gray-300',
            'checkbox' => 'border-gray-300',
            'group_errors' => 'hidden', // Hide error container when no validation state
        ],

        /*
        |--------------------------------------------------------------------------
        | Valid
        |--------------------------------------------------------------------------
        |
        | These classes will be applied to elements that have passed validation.
        |
        | These should be in the format '[element]' => '[class names]'
        | e.g. 'checkbox_label' => 'font-bold'
        |
        | See default-theme.php for a full example of configuring class names.
        |
        */
        'valid' => [
            'input' => 'border-green-500',
            'textarea' => 'border-green-500',
            'select' => 'border-green-500',
            'checkbox' => 'border-green-500',
            'group_errors' => 'hidden', // Hide error container when valid
        ],

        /*
        |--------------------------------------------------------------------------
        | Invalid
        |--------------------------------------------------------------------------
        |
        | These classes will be applied to elements that failed validation.
        |
        | These should be in the format '[element]' => '[class names]'
        | e.g. 'checkbox_label' => 'font-bold'
        |
        | See default-theme.php for a full example of configuring class names.
        |
        */
        'invalid' => [
            'input' => 'border-red-500',
            'textarea' => 'border-red-500',
            'select' => 'border-red-500',
            'checkbox' => 'border-red-500',
            'group_errors' => 'block text-red-600', // Show error container and style when invalid
        ],
    ],

];
