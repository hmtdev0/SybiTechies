<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class JobOpeningRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $jobOpeningId = $this->route('jobOpening')?->id;

        return [
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:170', 'alpha_dash', 'unique:job_openings,slug,'.$jobOpeningId],
            'department' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:100'],
            'type' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:20000'],
            'requirements' => ['nullable', 'string', 'max:20000'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
