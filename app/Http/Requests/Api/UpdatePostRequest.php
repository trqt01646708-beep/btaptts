<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255|unique:posts,title,' . $this->route('post'),
            'slug' => 'sometimes|required|string|max:255|unique:posts,slug,' . $this->route('post') . '|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'sometimes|required|string|min:10',
            'featured_image' => 'nullable|url',
            'category_id' => 'nullable|exists:categories,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'title.unique' => 'Title must be unique',
            'slug.required' => 'Slug is required',
            'slug.unique' => 'Slug must be unique',
            'slug.regex' => 'Slug format is invalid',
            'content.required' => 'Content is required',
            'content.min' => 'Content must be at least 10 characters',
            'featured_image.url' => 'Featured image must be a valid URL',
            'category_id.exists' => 'Category does not exist',
        ];
    }
}
