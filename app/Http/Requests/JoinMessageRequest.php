<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JoinMessageRequest extends FormRequest
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
            'reason' => 'required|max:5000',
            'url' => 'nullable|url',
            'rules' => 'required|accepted',
            'h-captcha-response' => 'required|HCaptcha'
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
            'rules.required' => __('Please accept the rules to join HackGreenville.'),
            'rules.accepted' => __('You must accept the rules to join HackGreenville.'),
            'h-captcha-response.required' => __('Please verify that you are not a robot.'),
            'h-captcha-response.captcha' => __('Captcha error! try again later or contact site admin.'),
        ];
    }
}
