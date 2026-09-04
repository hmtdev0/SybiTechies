<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProcessStepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'step_number' => ['nullable', 'integer', 'min:1'],
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:500'],
            'icon' => ['required', 'string', 'max:100'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
