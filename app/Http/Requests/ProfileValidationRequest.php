<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumberValidator;
use App\Rules\UrlValidator;
use Illuminate\Foundation\Http\FormRequest;

class ProfileValidationRequest extends FormRequest
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
            'about' => ['string'],
            'image' => ['string'],
            'company' => ['string'],
            'comp_function' => ['string'],
            'facebook' => ['string', new UrlValidator],
            'twitter' => ['string', new UrlValidator],
            'linkedin' => ['string', new UrlValidator],
            'phone_number' => ['string', new PhoneNumberValidator],
            'contact_email' => ['string', 'email', 'max:255'],
        ];
    }
}
