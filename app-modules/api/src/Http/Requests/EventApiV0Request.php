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
        ];
    }
}
