<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        $isCreate = $this->isMethod('post');

        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,'.$userId],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'password' => [$isCreate ? 'required' : 'nullable', 'confirmed', Password::min(8)],
        ];
    }

    /**
     * Only an existing Super Admin can grant the Super Admin role to
     * someone else — otherwise an Admin could hand themselves (or anyone
     * else) full, unrestricted access.
     */
    public function withValidator(ValidatorContract $validator): void
    {
        $validator->after(function (ValidatorContract $validator) {
            $roles = (array) $this->input('roles', []);

            if (in_array('Super Admin', $roles, true) && ! $this->user()->hasRole('Super Admin')) {
                $validator->errors()->add('roles', 'Only a Super Admin can assign the Super Admin role.');
            }
        });
    }
}
