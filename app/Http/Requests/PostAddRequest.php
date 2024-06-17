<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostAddRequest extends FormRequest
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
            'title' => ["required","string","max:100"],
            'description' => ["required","string"],
            'images' => ["required","array"],
            'images.*' => ["file","image"],
            'post_type' => ["required","in:1,2,3,4"]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => __('messages.post_messages.post_validation.required', ['attribute' => 'title']),
            'title.string' => __('messages.post_messages.post_validation.string', ['attribute' => 'title']),
            'title.max' => __('messages.post_messages.post_validation.max', ['attribute' => 'title', 'max' => 100]),
            
            'description.required' => __('messages.post_messages.post_validation.required', ['attribute' => 'description']),
            'description.string' => __('messages.post_messages.post_validation.string', ['attribute' => 'description']),
            
            'images.required' => __('messages.post_messages.post_validation.required', ['attribute' => 'images']),
            'images.array' => __('messages.post_messages.post_validation.array', ['attribute' => 'images']),
            
            'images.*.file' => __('messages.post_messages.post_validation.file', ['attribute' => 'image']),
            'images.*.image' => __('messages.post_messages.post_validation.image', ['attribute' => 'image']),
            
            'post_type.required' => __('messages.post_messages.post_validation.required', ['attribute' => 'post type']),
            'post_type.in' => __('messages.post_messages.post_validation.in', ['attribute' => 'post type']),
        ];
    }
}
