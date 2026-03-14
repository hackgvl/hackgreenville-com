<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

class ContactMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'contact' => 'required|email:rfc,dns',
            'message' => 'required|max:5000',
            'cf-turnstile-response' => ['required', new Turnstile]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'cf-turnstile-response.required' => __('Please verify that you are not a robot.'),
        ];
    }
}
