<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="TranslationRequest",
 *     type="object",
 *     title="Translation Request",
 *     required={"name", "title", "description"},
 *
 *     @OA\Property(property="name", type="string", minLength=2, maxLength=255, example="John Doe"),
 *     @OA\Property(property="title", type="string", minLength=3, maxLength=500, example="Welcome Message"),
 *     @OA\Property(property="description", type="string", minLength=10, maxLength=5000, example="This is a welcome message for our users."),
 *     @OA\Property(property="target_language", type="string", maxLength=2, example="es", enum={"es", "fr", "de", "it", "pt"})
 * )
 *
 * Handles validation and authorization for storing a translation.
 */
class StoreTranslationRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:255',
            'title' => 'required|string|min:3|max:500',
            'description' => 'required|string|min:10|max:5000',
            'target_language' => 'sometimes|string|size:2|in:es,fr,de,it,pt',
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
