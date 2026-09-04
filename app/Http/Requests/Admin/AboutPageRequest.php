<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AboutPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'mission_title' => ['nullable', 'string', 'max:150'],
            'mission_text' => ['nullable', 'string', 'max:1000'],
            'vision_title' => ['nullable', 'string', 'max:150'],
            'vision_text' => ['nullable', 'string', 'max:1000'],
            'story_title' => ['nullable', 'string', 'max:150'],
            'story_text' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
