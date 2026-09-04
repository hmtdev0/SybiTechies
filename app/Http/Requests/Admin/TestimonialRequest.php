<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'client_name' => ['required', 'string', 'max:150'],
            'company' => ['nullable', 'string', 'max:150'],
            'designation' => ['nullable', 'string', 'max:150'],
            'review' => ['required', 'string', 'max:1000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
