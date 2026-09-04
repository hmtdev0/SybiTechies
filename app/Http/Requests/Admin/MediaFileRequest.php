<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MediaFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'files' => ['required', 'array'],
            'files.*' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg,pdf', 'max:8192'],
        ];
    }
}
