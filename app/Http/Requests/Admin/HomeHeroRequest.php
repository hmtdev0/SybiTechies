<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HomeHeroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'badge_text' => ['nullable', 'string', 'max:150'],
            'title' => ['required', 'string', 'max:255'],
            'highlight_text' => ['nullable', 'string', 'max:150'],
            'typed_words' => ['nullable', 'string'],
            'description' => ['nullable', 'string', 'max:1000'],
            'btn1_text' => ['nullable', 'string', 'max:50'],
            'btn1_link' => ['nullable', 'string', 'max:255'],
            'btn2_text' => ['nullable', 'string', 'max:50'],
            'btn2_link' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    /**
     * The admin submits typed_words as one word/phrase per line — convert
     * to the JSON array the model expects.
     */
    public function typedWordsArray(): array
    {
        return collect(explode("\n", (string) $this->input('typed_words')))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
