<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ForgotPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function show(): View
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Always responds with the same generic message regardless of whether
     * the email matched an account, so this endpoint can't be used to
     * enumerate registered admin emails.
     */
    public function send(ForgotPasswordRequest $request): RedirectResponse
    {
        Password::broker('users')->sendResetLink($request->only('email'));

        return back()->with('success', 'If that email address is registered, a password reset link has been sent to it.');
    }
}
