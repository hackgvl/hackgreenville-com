<?php

namespace HackGreenville\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventApiV0Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'start_date' => [
                'nullable',
                'sometimes',
                'date_format:Y-m-d',
                'before_or_equal:end_date',
            ],
            'end_date' => [
                'nullable',
                'sometimes',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
            ],
            'tags' => [
                'nullable',
                'sometimes',
                'integer',
            ],
        ];
    }

    public function queryParameters()
    {
        return [
            'start_date' => [
                'description' => 'The start date for events filtering (inclusive).',
                'example' => '2026-01-01',
            ],
            'end_date' => [
                'description' => 'The end date for events filtering (inclusive).',
                'example' => '2100-12-31',
            ],
            'tags' => [
                'example' => null,
                'description' => 'Filter events by organization tag ID.',
            ],
        ];
    }
}
