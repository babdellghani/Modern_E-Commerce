<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:200', 'alpha_dash', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('categories')->ignore($this->category)],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}
