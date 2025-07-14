<?php

namespace HackGreenville\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrgsApiV0Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
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
            'tags' => [
                'example' => null,
                'description' => 'Filter organizations by organization tag ID.',
            ],
        ];
    }
}
