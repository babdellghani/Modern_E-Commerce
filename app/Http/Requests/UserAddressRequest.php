<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'max:45'],
            'address_1' => ['required', 'string', 'max:200'],
            'address_2' => ['nullable', 'string', 'max:200'],
            'city' => ['required', 'string', 'max:200'],
            'state' => ['nullable', 'string', 'max:200'],
            'postal_code' => ['required', 'string', 'max:45'],
            'country_code' => ['required', 'string', 'max:5'],
            'phone' => ['required', 'string', 'max:45'],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }
}
