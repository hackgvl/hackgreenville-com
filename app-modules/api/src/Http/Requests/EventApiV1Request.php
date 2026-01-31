<?php

namespace HackGreenville\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventApiV1Request extends FormRequest
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

            'start_date' => [
                'nullable',
                'date_format:Y-m-d',
                'before_or_equal:end_date',
            ],
            'end_date' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
            ],

            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'org_name' => ['nullable', 'string', 'max:255'],
            'service' => ['nullable', 'string', 'max:255'],
            'is_paid' => ['nullable', 'in:0,1,true,false'],
            'min_rsvp' => ['nullable', 'integer', 'min:0'],
            'max_rsvp' => [
                'nullable',
                'integer',
                'min:0',
                'gte:min_rsvp'
            ],
            'venue_city' => ['nullable', 'string', 'max:255'],
            'venue_state' => ['nullable', 'string', 'size:2'],
            'is_paid' => [
                'nullable',
                'in:null,true,false',
            ],

            'sort_by' => [
                'nullable',
                'string',
                Rule::in(['active_at', 'event_name', 'group_name', 'rsvp_count', 'created_at'])
            ],
            'sort_direction' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
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
            'start_date' => [
                'description' => 'The start date for events filtering (inclusive). Future event data may be limited. Please see the [Event API docs](https://github.com/hackgvl/hackgreenville-com/blob/develop/EVENTS_API.md) for information about event data limitations.',
                'example' => '2026-01-01',
            ],
            'end_date' => [
                'description' => 'The end date for events filtering (inclusive). Future event data may be limited. Please see the [Event API docs](https://github.com/hackgvl/hackgreenville-com/blob/develop/EVENTS_API.md) for information about event data limitations.',
                'example' => '2100-12-31',
            ],
            'tags' => [
                'example' => null,
                'description' => 'Filter events by organization tag ID.',
            ],
            'name' => [
                'example' => null,
                'description' => 'Filter events by event name (the "event_name" property)',
            ],
            'org_name' => ['example' => null, 'description' => 'The name of the organization associated with the event (the "group_name" property)'],
            'service' => ['example' => null, 'description' => 'The service that imported the event (meetup_graphql, eventbrite, etc.)'],
            'min_rsvp' => [
                'example' => null,
            ],
            'max_rsvp' => [
                'example' => null,
            ],
            'venue_city' => ['example' => null,],
            'venue_state' => ['example' => null,],
            'is_paid' => [
                'example' => null,
                'description' => 'Filter events that require payment (null means we currently cannot determine if event is paid)'
            ],
            'sort_by' => ['example' => 'event_name'],
            'sort_direction' => ['example' => 'asc',],
        ];
    }

    public function messages()
    {
        return [
            'per_page.min' => 'The per page value must be at least 1.',
            'per_page.max' => 'The per page value cannot exceed 100.',
            'page.min' => 'The page value must be at least 1.',
            'start_date.date_format' => 'The start date must be in the format YYYY-MM-DD.',
            'start_date.before_or_equal' => 'The start date must be before or equal to the end date.',
            'end_date.date_format' => 'The end date must be in the format YYYY-MM-DD.',
            'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
            'tags.*.exists' => 'The selected tag does not exist.',
            'min_rsvp.min' => 'The minimum RSVP count must be at least 0.',
            'max_rsvp.min' => 'The maximum RSVP count must be at least 0.',
            'max_rsvp.gte' => 'The maximum RSVP count must be greater than or equal to the minimum RSVP count.',
            'venue_state.size' => 'The venue state must be a 2-letter state code.',
            'sort_by.in' => 'The sort by field must be one of: active_at, event_name, group_name, rsvp_count, created_at.',
            'sort_direction.in' => 'The sort direction must be either asc or desc.',
        ];
    }
}
