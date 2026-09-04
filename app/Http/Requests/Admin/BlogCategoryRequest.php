<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('blogCategory')?->id;

        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:120', 'alpha_dash', 'unique:blog_categories,slug,'.$categoryId],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
