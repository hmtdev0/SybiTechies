<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'resume' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
