<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation and authorization for storing a translation.
 *
 * @package App\Http\Requests
 */
class StoreTranslationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
            'name' => 'required|string|min:2|max:255',
            'title' => 'required|string|min:3|max:500',
            'description' => 'required|string|min:10|max:5000',
            'target_language' => 'sometimes|string|size:2|in:es,fr,de,it,pt'
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.min' => 'The name must be at least 2 characters.',
            'title.required' => 'The title field is required.',
            'title.min' => 'The title must be at least 3 characters.',
            'description.required' => 'The description field is required.',
            'description.min' => 'The description must be at least 10 characters.',
            'target_language.in' => 'The target language must be one of: es, fr, de, it, pt.',
        ];
    }
}