<?php

namespace HackGreenville\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MapLayersApiV1Request extends FormRequest
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
            'title' => ['nullable', 'string', 'max:255'],
            'sort_by' => [
                'nullable',
                'string',
                Rule::in(['title', 'updated_at', 'created_at']),
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
            'sort_by.in' => 'The sort by field must be one of: title, updated_at, created_at.',
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
            'title' => ['example' => null, 'description' => 'Filter map layers by title'],
            'sort_by' => ['example' => 'title'],
            'sort_direction' => ['example' => 'asc'],
        ];
    }
}
