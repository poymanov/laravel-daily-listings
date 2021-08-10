<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth\RegistrationStepTwo;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'phone'   => 'required|string|min:3',
            'address' => 'required|string|min:3',
            'city_id' => 'required|integer|exists:cities,id',
        ];
    }
}
