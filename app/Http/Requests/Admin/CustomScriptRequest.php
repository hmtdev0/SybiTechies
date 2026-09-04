<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CustomScriptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'placement' => ['required', 'in:head,footer'],
            // Deliberately not sanitized/stripped like the rich-text fields
            // elsewhere in the admin — this field's entire purpose is to hold
            // raw <script>/<meta>/<noscript> markup (GA, pixels, chat widgets)
            // that gets echoed unescaped into the public site. It's gated by
            // the "manage settings" permission, same trust level as SMTP
            // credentials or site settings.
            'code' => ['required', 'string'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
