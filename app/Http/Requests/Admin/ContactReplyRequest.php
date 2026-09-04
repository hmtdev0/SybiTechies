<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ContactReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reply_subject' => ['required', 'string', 'max:255'],
            'admin_reply' => ['required', 'string', 'max:20000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,webp,gif,pdf,doc,docx,xls,xlsx,csv,txt,zip'],
        ];
    }
}
