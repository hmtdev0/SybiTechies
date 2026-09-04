<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $serviceId = $this->route('service')?->id;

        return [
            'icon' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:170', 'alpha_dash', 'unique:services,slug,'.$serviceId],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'short_description' => ['required', 'string', 'max:255'],
            'full_description' => ['nullable', 'string', 'max:20000'],
            'is_featured' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],

            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string', 'max:255'],

            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_meta_description' => ['nullable', 'string', 'max:500'],
            'seo_meta_keywords' => ['nullable', 'string', 'max:500'],
            'seo_canonical_url' => ['nullable', 'url', 'max:255'],
            'seo_og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'seo_og_title' => ['nullable', 'string', 'max:255'],
            'seo_og_description' => ['nullable', 'string', 'max:500'],
            'seo_schema_json' => ['nullable', 'json'],
            'seo_robots' => ['nullable', 'string', 'max:100'],
        ];
    }
}
