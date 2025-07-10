<?php

namespace HackGreenville\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrgsApiV1Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],

            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'focus_area' => ['nullable', 'string', 'max:255'],
            'organization_type' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:255'],
            'established_from' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'established_to' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . date('Y'),
                'gte:established_from'
            ],

            'sort_by' => [
                'nullable',
                'string',
                Rule::in(['title', 'city', 'established_at', 'updated_at', 'created_at'])
            ],
            'sort_direction' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
        ];
    }

    public function messages()
    {
        return [
            'per_page.min' => 'The per page value must be at least 1.',
            'per_page.max' => 'The per page value cannot exceed 100.',
            'page.min' => 'The page value must be at least 1.',
            'tags.*.exists' => 'The selected tag does not exist.',
            'established_from.min' => 'The established from year must be at least 1900.',
            'established_from.max' => 'The established from year cannot exceed the current year.',
            'established_to.min' => 'The established to year must be at least 1900.',
            'established_to.max' => 'The established to year cannot exceed the current year.',
            'established_to.gte' => 'The established to year must be greater than or equal to the established from year.',
            'sort_by.in' => 'The sort by field must be one of: title, city, established_at, updated_at, created_at.',
            'sort_direction.in' => 'The sort direction must be either asc or desc.',
        ];
    }

    public function queryParameters()
    {
        return [
            'per_page' => [
                'example' => 50,
                'description' => 'The number of items to show per page',
            ],
            'page' => [
                'example' => 1,
                'description' => 'The current page of items to display',
            ],
            'tags' => [
                'example' => null,
                'description' => 'Filter organizations by tag ID.',
            ],
            'tags.*' => [
                'example' => null,
                'description' => 'Filter organizations by tag ID.',
            ],
            'title' => ['example' => null],
            'city' => ['example' => null],
            'focus_area' => ['example' => null, 'description' => 'The organization category (Entrpreneurship, Security, etc.)'],
            'organization_type' => ['example' => null, 'description' => 'The organization type (Meetup Groups, Code Schools, etc.)'],
            'status' => ['example' => null, 'description' => 'The organization status (active, inactive, etc.)'],
            'established_from' => ['example' => null, 'description' => 'The year the organization was established'],
            'established_to' => ['example' => null, 'description' => 'The year the organization was dissolved'],

            'sort_by' => [
                'example' => 'title',
            ],
            'sort_direction' => ['example' => 'asc'],
        ];
    }
}
