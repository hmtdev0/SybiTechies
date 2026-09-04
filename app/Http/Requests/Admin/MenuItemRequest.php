<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'menu_id' => ['required', 'exists:menus,id'],
            'label' => ['required', 'string', 'max:100'],
            'url' => ['required', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_new_tab' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
