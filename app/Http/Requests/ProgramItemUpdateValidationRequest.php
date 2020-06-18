<?php

namespace App\Http\Requests;

use App\Rules\ProgramExistValidator;
use Illuminate\Foundation\Http\FormRequest;

class ProgramItemUpdateValidationRequest extends FormRequest
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
            'name' => ['required', 'max:255'],
            'description' => ['required'],
            'date' => ['date'],
            'active' => ['required', 'boolean'],
        ];
    }
}
