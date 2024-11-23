<?php

// app/Http/Requests/PostRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Set to true if you want to allow all users to use this request.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'author' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image validation
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The post name is required.',
            'date.required' => 'The post date is required.',
            'author.required' => 'The author name is required.',
            'content.required' => 'The content is required.',
            'image.image' => 'The image must be a valid image file.',
            'image.max' => 'The image size must be less than 2MB.',
        ];
    }
}
