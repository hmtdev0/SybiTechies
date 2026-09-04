<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TechnologyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:500'],
            'color' => ['nullable', 'string', 'max:20'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
