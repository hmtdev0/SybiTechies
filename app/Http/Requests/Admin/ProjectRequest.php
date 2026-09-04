<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $projectId = $this->route('project')?->id;

        return [
            // Core
            'project_category_id' => ['nullable', 'exists:project_categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:170', 'alpha_dash', 'unique:projects,slug,'.$projectId],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'client' => ['nullable', 'string', 'max:150'],
            'duration' => ['nullable', 'string', 'max:100'],
            // Rich-text (Quill) HTML — generous limit since formatting tags
            // and embedded <img> URLs add overhead beyond the visible text.
            'description' => ['nullable', 'string', 'max:20000'],
            'challenges' => ['nullable', 'string', 'max:20000'],
            'solutions' => ['nullable', 'string', 'max:20000'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],

            // Relations
            'technologies' => ['nullable', 'array'],
            'technologies.*' => ['exists:technologies,id'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string', 'max:255'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            // SEO (prefixed to avoid colliding with the fields above)
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
