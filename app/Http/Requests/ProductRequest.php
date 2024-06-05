<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'slug' => ['required', 'string', 'max:200', Rule::unique('products')->ignore($this->product)],  
            'description' => ['nullable', 'required_if:published,true', 'string'],
            'quantity' => ['required', 'integer', 'gt:0'],
            'price' => ['required', 'numeric', 'gt:0'],
            'published' => ['required', 'boolean'],
            'in_stock' => ['required', 'boolean'],
            'category_id' => ['nullable', 'required_if:published,true', 'exists:categories,id'],
            'brand_id' => ['nullable', 'required_if:published,true', 'exists:brands,id'],
            'created_by' => ['nullable', 'exists:users,id'],
            'updated_by' => ['nullable', 'exists:users,id'],
            'deleted_by' => ['nullable', 'exists:users,id'],
        ];
    }
}
